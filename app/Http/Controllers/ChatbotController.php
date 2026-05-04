<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
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
        $apiKey = env('GROQ_API_KEY');
        $userId = session('user.id');

        $history = DB::table('ai_messages')
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
            'sql' => null,
            'error' => null,
        ];

        if (!$userQuery) {
            return $this->formatResponse('Halo! Ada yang bisa Jokopus bantu cari?', null, $debugInfo);
        }

        $intent = strtoupper(trim($this->askGroq("Intent: '$userQuery'. SEARCH/CASUAL. 1 word ONLY.", $apiKey, true)));

        if (strpos($intent, 'CASUAL') !== false) {
            $answer = $this->askGroq($userQuery, $apiKey, false, $history);
            $this->saveHistoryToDb($userQuery, $answer);
            return $this->formatResponse($answer, null, $debugInfo);
        }

        $schemaContext = "buku(id,judul,ringkasan,id_penulis,id_kategori,rating); penulis(id,nama); kategori(id,nama);";
        $promptSql = "Generate ONE MySQL query for: '$userQuery'. 
        Tables: $schemaContext. 
        Rules: 
        - Use JOIN. 
        - If user asks generally, ORDER BY rating DESC LIMIT 10. 
        - If specific, use WHERE LIKE. 
        - Output ONLY the SQL string, NO explanations, NO multiple options.";
        $sql = $this->askGroq($promptSql, $apiKey, true);

        if ($sql === 'ERROR_CONNECTION' || $sql === 'ERROR') {
            return $this->formatResponse("Maaf, koneksi ke otak AI saya terputus. Coba lagi nanti?", null, $debugInfo);
        }

        $sql = trim(str_replace(['```sql', '```', '`', ';'], '', $sql));
        if (!str_contains(strtoupper($sql), 'SELECT')) {
            return $this->formatResponse("Saya kesulitan merangkai pencarian. Bisa perjelas pertanyaannya?", $sql, $debugInfo);
        }

        try {
            $dbResult = DB::select($sql);
            $jsonResult = json_encode($dbResult);

            $promptFinal = "
            Role: Asisten Perpustakaan Jokopus.
            Strict Rules:
            1. HANYA rekomendasikan buku yang ada di: $jsonResult.
            2. Jika $jsonResult kosong [], JANGAN sebut judul buku apapun. Katakan maaf dan sarankan user mencari topik lain.
            3. DILARANG KERAS mengarang judul buku dari ingatan internalmu.
            4. Jawab dalam Bahasa Indonesia yang ramah dan singkat.
            
            User Query: $userQuery";
            $answer = $this->askGroq($promptFinal, $apiKey, false, $history);
            $this->saveHistoryToDb($userQuery, $answer);

            return $this->formatResponse($answer, $sql, $debugInfo);
        } catch (\Exception $e) {
            $debugInfo['error'] = $e->getMessage();
            return $this->formatResponse("Aduh, sistem rak saya macet. Coba tanya lagi?", $sql, $debugInfo);
        }
    }

    private function askGroq($prompt, $apiKey, $isSqlMode = false, $history = [])
    {
        $systemContent = $isSqlMode
            ? "You are a raw SQL generator. No talk, just code."
            : "Anda Jokopus, asisten perpustakaan. Jawab singkat & ramah.";

        $messages = [['role' => 'system', 'content' => $systemContent]];

        if (!$isSqlMode && !empty($history)) {
            foreach ($history as $msg) {
                $messages[] = $msg;
            }
        }

        $messages[] = ['role' => 'user', 'content' => $prompt];

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . trim($apiKey),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'openai/gpt-oss-120b',
                        'messages' => $messages,
                        'temperature' => 0.1,
                        'max_tokens' => 512,
                    ]);

            if ($response->failed()) {
                \Log::error('Groq API Error: ' . $response->body());
                return 'ERROR_CONNECTION';
            }

            $result = $response->json();
            return $result['choices'][0]['message']['content'] ?? 'ERROR';
        } catch (\Exception $e) {
            \Log::error('Groq Connection Exception: ' . $e->getMessage());
            return 'ERROR_CONNECTION';
        }
    }

    private function saveHistoryToDb($query, $answer)
    {
        $userId = session('user.id');
        $data = [
            ['user_id' => $userId, 'role' => 'user', 'content' => $query, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $userId, 'role' => 'assistant', 'content' => $answer, 'created_at' => now(), 'updated_at' => now()]
        ];
        DB::table('ai_messages')->insert($data);
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