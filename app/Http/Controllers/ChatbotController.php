<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ChatbotController extends Controller
{
    protected $whitelistTables = ['buku', 'kategori', 'penulis', 'tag', 'buku_tag', 'peminjaman', 'ratings', 'komentar_buku', 'users'];
    protected $debugMode;

    public function __construct()
    {
        $this->debugMode = env('CHATBOT_DEBUG', true);
    }

    public function getHistory()
    {
        $userId = session('user.id');
        $messages = DB::table('ai_messages')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function index()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $userId = session('user.id');

        $history = DB::table('ai_messages')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        $user = DB::table('users')->where('id', $userId)->first();

        return view('chat.jokobot', compact('user', 'history'));
    }

    public function chat(Request $request)
    {
        set_time_limit(0);
        $userQuery = trim($request->input('message'));
        $apiKey = env('NVIDIA_API_KEY');
        $history = session()->get('chat_history', []);
        $userId = session('user.id');

        $historyForAi = DB::table('ai_messages')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->reverse()
            ->map(function ($msg) {
                return ['role' => $msg->role, 'content' => $msg->content];
            })->toArray();

        $debugInfo = [
            'enabled' => $this->debugMode,
            'steps' => [],
            'sql' => null,
            'error' => null,
            'timestamp' => now()->toDateTimeString()
        ];

        if (!$userQuery) {
            return $this->formatResponse('Halo! Ada yang bisa Jokopus bantu cari?', null, $debugInfo);
        }

        $intentPrompt = "Classify intent: '$userQuery'. Categories: SEARCH (looking for books/info), CASUAL (greetings/chat). Return 1 word ONLY.";
        $intent = strtoupper(trim($this->askNvidia($intentPrompt, $apiKey, true)));

        if ($intent === 'CASUAL') {
            $answer = $this->askNvidia($userQuery, $apiKey, false, $history);
            $this->saveHistoryToDb($userQuery, $answer);
            $this->saveHistory($userQuery, $answer);
            return $this->formatResponse($answer, null, $debugInfo);
        }

        $schemaContext = "
            Table buku(id, judul, ringkasan, id_penulis, id_kategori, rating, price);
            Table penulis(id, nama);
            Table kategori(id, nama);
            Table peminjaman(id, id_user, id_buku, status, tanggal_pinjam);
        ";

        $today = now()->toDateString();

        $promptSql = "
            Generate MySQL SELECT. 
            Current Date: $today.
            Tables: $schemaContext. 
            Rules:
            - JOIN buku with penulis and kategori.
            - EXTRACT keyword from: '$userQuery'. 
            - If user asks for general advice/recommendation without specific title, ORDER BY rating DESC LIMIT 10.
            - If user mentions title/author/topic, use WHERE b.judul LIKE '%keyword%' OR p.nama LIKE '%keyword%'.
            - SELECT: b.id, b.judul, b.ringkasan, p.nama as penulis, k.nama as kategori.
            - Return ONLY raw SQL code.
        ";

        $sql = $this->askNvidia($promptSql, $apiKey, true);
        $sql = trim(str_replace(['```sql', '```', '`', ';'], '', $sql));

        try {
            $dbResult = DB::select($sql);
            $jsonResult = json_encode($dbResult);

            $promptFinal = "
                You are Jokopus, a librarian.
                Context Data: $jsonResult.
                User Query: $userQuery.
                Rules:
                1. If Context is [], say you don't have that specific book and offer other categories.
                2. If Context has data, recommend them warmly in Indonesian.
                3. Mention the author and category.
            ";

            $answer = $this->askNvidia($promptFinal, $apiKey, false, $history);
            $this->saveHistoryToDb($userQuery, $answer);
            $this->saveHistory($userQuery, $answer);

            return $this->formatResponse($answer, $sql, $debugInfo);
        } catch (\Exception $e) {
            $debugInfo['error'] = $e->getMessage();
            return $this->formatResponse("Aduh, sistem rak saya sedikit macet. Bisa coba ulangi pertanyaannya?", $sql, $debugInfo);
        }
    }

    private function askNvidia($prompt, $apiKey, $isSqlMode = false, $history = [])
    {
        $messages = [];
        $systemContent = $isSqlMode
            ? "You are a precise SQL generator. Output only raw SQL."
            : "Anda adalah Jokopus, asisten perpustakaan cerdas. Gunakan Bahasa Indonesia.";

        $messages[] = ['role' => 'system', 'content' => $systemContent];

        if (!$isSqlMode && !empty($history)) {
            foreach ($history as $msg) {
                $messages[] = $msg;
            }
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . trim($apiKey),
                    'Accept' => 'application/json',
                ])
                ->post('https://integrate.api.nvidia.com/v1/chat/completions', [
                    'model' => 'nvidia/nemotron-3-super-120b-a12b',
                    'messages' => $messages,
                    'temperature' => 1,
                    'top_p' => 0.95,
                    'max_tokens' => 2048,
                ]);

            $result = $response->json();
            return $result['choices'][0]['message']['content'] ?? 'ERROR';
        } catch (\Exception $e) {
            return 'ERROR_CONNECTION';
        }
    }

    private function saveHistory($query, $answer)
    {
        $history = session()->get('chat_history', []);
        $history[] = ['role' => 'user', 'content' => $query];
        $history[] = ['role' => 'assistant', 'content' => $answer];
        if (count($history) > 10)
            $history = array_slice($history, -10);
        session(['chat_history' => $history]);
    }

    private function saveHistoryToDb($query, $answer)
    {
        $userId = session('user.id');

        // Simpan pesan user
        DB::table('ai_messages')->insert([
            'user_id' => $userId,
            'role' => 'user',
            'content' => $query,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simpan jawaban bot
        DB::table('ai_messages')->insert([
            'user_id' => $userId,
            'role' => 'assistant',
            'content' => $answer,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function formatResponse($answer, $sql = null, $debugInfo = [])
    {
        return response()->json([
            'status' => 'success',
            'answer' => $answer,
            'sql_debug' => $sql,
            'debug' => $this->debugMode ? $debugInfo : null
        ]);
    }
}