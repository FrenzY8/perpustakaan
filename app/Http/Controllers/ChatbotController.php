<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $whitelistTables = ['buku', 'buku_favorit_user', 'tag', 'peminjaman', 'kategori', 'penulis', 'ratings', 'komentar_buku'];
    protected $debugMode;

    public function __construct()
    {
        $this->debugMode = env('CHATBOT_DEBUG', true);
    }

    public function index()
    {
        if (!session()->has('user')) return redirect('/login');
        $user = DB::table('users')->where('id', session('user.id'))->first();
        return view('chat.jokobot', compact('user'));
    }

    public function chat(Request $request)
    {
        $userQuery = trim($request->input('message'));
        $apiKey = env('NVIDIA_API_KEY');
        $history = session()->get('chat_history', []);
        
        $debugInfo = [
            'enabled' => $this->debugMode,
            'steps' => [],
            'sql' => null,
            'timestamp' => now()->toDateTimeString()
        ];

        if (!$userQuery) {
            return $this->formatResponse('Halo! Ada yang bisa Jokopus bantu cari?', null, $debugInfo);
        }

        $intentPrompt = "Classify intent: '$userQuery'. Categories: SEARCH, CASUAL. Return 1 word ONLY.";
        $intent = strtoupper(trim($this->askNvidia($intentPrompt, $apiKey, true)));

        if ($intent === 'CASUAL') {
            $answer = $this->askNvidia($userQuery, $apiKey, false, $history);
            $this->saveHistory($userQuery, $answer);
            return $this->formatResponse($answer, null, $debugInfo);
        }

        $schemaInfo = $this->getWhitelistedSchema();
        $promptSql = "
            Generate MySQL SELECT. 
            Tables: $schemaInfo. 
            Rules: Use JOIN for penulis/kategori, LIMIT 10, use LIKE, return ONLY SQL code.
            Query: $userQuery
        ";

        $sql = $this->askNvidia($promptSql, $apiKey, true);
        $sql = trim(str_replace(['```sql', '```', '`', ';'], '', $sql));

        try {
            $dbResult = DB::select($sql);
            $jsonResult = json_encode($dbResult);

            $promptFinal = "
                You are Jokopus, a helpful librarian.
                User: $userQuery
                Data: $jsonResult
                Rule: If data empty, suggest other genres politely. If data exists, list them nicely.
                Language: Indonesian.
            ";

            $answer = $this->askNvidia($promptFinal, $apiKey, false, $history);
            $this->saveHistory($userQuery, $answer);

            return $this->formatResponse($answer, $sql, $debugInfo);

        } catch (\Exception $e) {
            $answer = "Maaf, sepertinya saya kesulitan mengakses rak buku saat ini. Bisa coba kata kunci lain?";
            return $this->formatResponse($answer, $sql, $debugInfo);
        }
    }

    private function askNvidia($prompt, $apiKey, $isSqlMode = false, $history = [])
    {
        $messages = [];
        $systemContent = $isSqlMode 
            ? "You are a precise SQL generator. Output only raw SQL."
            : "Anda adalah Jokopus, asisten perpustakaan cerdas. Selalu ramah dan gunakan Bahasa Indonesia.";

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
                    'model' => 'mistralai/devstral-2-123b-instruct-2512',
                    'messages' => $messages,
                    'temperature' => $isSqlMode ? 0.0 : 0.7,
                    'max_tokens' => 1024,
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
        
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }
        
        session(['chat_history' => $history]);
    }

    private function getWhitelistedSchema()
    {
        $schemaText = "";
        foreach ($this->whitelistTables as $table) {
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $schemaText .= "Table $table (" . implode(', ', $columns) . "); ";
            }
        }
        return $schemaText;
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