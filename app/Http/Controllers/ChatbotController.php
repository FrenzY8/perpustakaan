<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $whitelistTables = ['buku', 'buku_favorit_user', 'tag', 'peminjaman', 'kategori', 'penulis', 'komentar_buku'];
    protected $debugMode;

    public function __construct()
    {
        $this->debugMode = env('CHATBOT_DEBUG', true);
    }

    public function index()
    {
        if (!session()->has('user'))
            return redirect('/login');

        $user = DB::table('users')->where('id', session('user.id'))->first();
        return view('chat.jokobot', compact('user'));
    }

    public function chat(Request $request)
    {
        $userQuery = trim($request->input('message'));
        $sessionId = $request->session()->getId();
        $apiKey = env('NVIDIA_API_KEY');

        $debugInfo = [
            'enabled' => $this->debugMode,
            'steps' => [],
            'sql' => null,
            'sql_result' => null,
            'error' => null,
            'timestamp' => now()->toDateTimeString()
        ];

        if (!$userQuery) {
            return $this->formatResponse('Halo! Mau cari buku apa hari ini?', null, $debugInfo);
        }

        $memory = Cache::get("chat_memory_$sessionId", []);
        $memory[] = ['role' => 'user', 'content' => $userQuery];
        if ($this->debugMode) {
            $debugInfo['steps'][] = '1. Mengklasifikasikan intent...';
        }

        $intentPrompt = "
User message: $userQuery

Classify intent.

Possible intents:
DATABASE
CASUAL

Return only one word.
";

        $intent = strtoupper(trim($this->askNvidia($intentPrompt, $apiKey, true)));

        if ($this->debugMode) {
            $debugInfo['steps'][] = "   Intent terdeteksi: $intent";
        }

        if (str_contains($intent, 'CASUAL')) {
            $chatPrompt = "
You are Jokopus library assistant.

Conversation history:
" . json_encode($memory) . "

User message:
$userQuery

Answer naturally and helpfully.
";

            $answer = $this->askNvidia($chatPrompt, $apiKey, false);

            $memory[] = ['role' => 'assistant', 'content' => $answer];
            Cache::put("chat_memory_$sessionId", $memory, 3600);

            return $this->formatResponse($answer, null, $debugInfo);
        }
        if ($this->debugMode) {
            $debugInfo['steps'][] = '2. Membuat query SQL...';
        }

        $schemaInfo = $this->getDetailedSchema();

        $promptSql = "
You are an expert SQL query generator for a library database.

Database Schema:
$schemaInfo

Table Relationships:
- buku.id_penulis = penulis.id  (A book belongs to an author)
- buku.id_kategori = kategori.id (A book belongs to a category)
- buku.id = komentar_buku.id_buku (A book has many comments)
- buku.id = buku_favorit_user.id_buku (A book can be favorited by users)

IMPORTANT SEARCH RULES:
1. When searching for books by author name, you MUST JOIN with the 'penulis' table
2. When searching for books by publisher, search in the 'penerbit' column of 'buku' table
3. When searching for books by title, search in the 'judul' column
4. Always search in multiple relevant fields when appropriate
5. Use LIKE with % wildcards for partial matching
6. Always include buku.judul in the SELECT statement

User question: \"$userQuery\"

Generate a SINGLE SQL query that best answers this question.
Return ONLY the SQL query, no explanations, no markdown formatting.
";

        $sql = $this->askNvidia($promptSql, $apiKey, true);

        if (str_starts_with($sql, 'ERROR')) {
            $debugInfo['error'] = 'AI tidak dapat diakses';
            return $this->formatResponse('Maaf, saya sedang mengalami gangguan. Silakan coba lagi.', null, $debugInfo);
        }

        $sql = trim(str_replace(['```sql', '```', '`', ';'], '', $sql));

        if ($this->debugMode) {
            $debugInfo['sql'] = $sql;
            $debugInfo['steps'][] = "   SQL generated: " . substr($sql, 0, 100) . (strlen($sql) > 100 ? '...' : '');
        }
        if (!str_starts_with(strtolower(trim($sql)), 'select')) {
            if ($this->debugMode) {
                $debugInfo['steps'][] = '   Bukan query SELECT, fallback ke mode casual';
            }

            $fallbackPrompt = "
You are Jokopus library assistant.

User asked: \"$userQuery\"

I couldn't generate a proper database query for this.
Please suggest some books or ask for clarification in a friendly way.
Answer in Indonesian.
";

            $answer = $this->askNvidia($fallbackPrompt, $apiKey, false);

            return $this->formatResponse($answer, null, $debugInfo);
        }
        if ($this->debugMode) {
            $debugInfo['steps'][] = '3. Menjalankan query database...';
        }

        try {
            $startTime = microtime(true);
            $dbResult = DB::select($sql);
            $queryTime = round((microtime(true) - $startTime) * 1000, 2);

            if ($this->debugMode) {
                $debugInfo['sql_result'] = [
                    'row_count' => count($dbResult),
                    'data' => count($dbResult) <= 5 ? $dbResult : 'First 5 of ' . count($dbResult) . ' rows',
                    'query_time_ms' => $queryTime
                ];
                $debugInfo['steps'][] = "   Query selesai dalam {$queryTime}ms, menemukan " . count($dbResult) . " baris";
            }

            Log::info('Chatbot Query Executed', [
                'session_id' => $sessionId,
                'sql' => $sql,
                'row_count' => count($dbResult),
                'query_time_ms' => $queryTime
            ]);
            if (count($dbResult) === 0) {
                if ($this->debugMode) {
                    $debugInfo['steps'][] = '   Hasil kosong, mencoba pencarian alternatif...';
                }

                $alternativeResult = $this->tryAlternativeSearch($userQuery, $debugInfo);
                if ($alternativeResult) {
                    $dbResult = $alternativeResult;
                    if ($this->debugMode) {
                        $debugInfo['steps'][] = '   Pencarian alternatif berhasil!';
                    }
                }
            }

            $jsonResult = json_encode($dbResult);
            if ($this->debugMode) {
                $debugInfo['steps'][] = '4. Membuat response dari AI...';
            }

            $promptFinal = "
You are Jokopus library assistant.

User question: \"$userQuery\"

Database results:
$jsonResult

Please respond naturally in Indonesian:
- If results are found, list the books nicely with their details
- If no results are found, suggest related searches or ask for clarification
- Be friendly and helpful
";

            $answer = $this->askNvidia($promptFinal, $apiKey, false);

            $memory[] = ['role' => 'assistant', 'content' => $answer];
            Cache::put("chat_memory_$sessionId", $memory, 3600);

            return $this->formatResponse($answer, $sql, $debugInfo);

        } catch (\Exception $e) {
            Log::error('Chatbot Database Error', [
                'session_id' => $sessionId,
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);

            if ($this->debugMode) {
                $debugInfo['error'] = $e->getMessage();
                $debugInfo['steps'][] = '   ERROR: ' . $e->getMessage();
            }

            $errorPrompt = "
User asked: \"$userQuery\"

There was an error: {$e->getMessage()}

Please apologize and ask the user to try again with different keywords.
Answer in Indonesian.
";

            $answer = $this->askNvidia($errorPrompt, $apiKey, false);

            return $this->formatResponse($answer, $sql, $debugInfo);
        }
    }
    private function tryAlternativeSearch($userQuery, &$debugInfo)
    {
        // Ekstrak kata kunci dari query user
        $keywords = $this->extractKeywords($userQuery);

        if (empty($keywords)) {
            return null;
        }

        if ($this->debugMode) {
            $debugInfo['steps'][] = '   Keywords extracted: ' . implode(', ', $keywords);
        }
        try {
            $authorSql = "
                SELECT b.*, p.nama as penulis_nama 
                FROM buku b 
                JOIN penulis p ON b.id_penulis = p.id 
                WHERE " . implode(' OR ', array_map(function ($kw) {
                return "p.nama LIKE '%" . addslashes($kw) . "%'";
            }, $keywords));

            $authorResults = DB::select($authorSql);
            if (count($authorResults) > 0) {
                if ($this->debugMode) {
                    $debugInfo['steps'][] = '   Found ' . count($authorResults) . ' results by author';
                }
                return $authorResults;
            }
        } catch (\Exception $e) {
            Log::warning('Alternative author search failed', ['error' => $e->getMessage()]);
        }
        try {
            $titleSql = "
                SELECT * FROM buku 
                WHERE " . implode(' OR ', array_map(function ($kw) {
                return "judul LIKE '%" . addslashes($kw) . "%'";
            }, $keywords));

            $titleResults = DB::select($titleSql);
            if (count($titleResults) > 0) {
                if ($this->debugMode) {
                    $debugInfo['steps'][] = '   Found ' . count($titleResults) . ' results by title';
                }
                return $titleResults;
            }
        } catch (\Exception $e) {
            Log::warning('Alternative title search failed', ['error' => $e->getMessage()]);
        }

        return null;
    }
    private function extractKeywords($query)
    {
        $stopWords = ['cari', 'buku', 'yang', 'ada', 'berjudul', 'karya', 'karangan', 'tentang', 'mengenai', 'saya', 'mau', 'ingin', 'tolong'];
        $words = preg_split('/\s+/', strtolower($query));
        $keywords = array_filter($words, function ($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });

        return array_values($keywords);
    }
    private function getDetailedSchema()
    {
        $schemaText = "";
        if (Schema::hasTable('buku')) {
            $columns = Schema::getColumnListing('buku');
            $schemaText .= "Table buku (";
            $schemaText .= implode(', ', array_map(function ($col) {
                if ($col == 'judul')
                    return "$col (book title)";
                if ($col == 'penerbit')
                    return "$col (publisher)";
                if ($col == 'id_penulis')
                    return "$col (foreign key to penulis.id)";
                if ($col == 'id_kategori')
                    return "$col (foreign key to kategori.id)";
                return $col;
            }, $columns));
            $schemaText .= ")\n";
        }

        if (Schema::hasTable('penulis')) {
            $columns = Schema::getColumnListing('penulis');
            $schemaText .= "Table penulis (";
            $schemaText .= implode(', ', array_map(function ($col) {
                if ($col == 'nama')
                    return "$col (author name)";
                return $col;
            }, $columns));
            $schemaText .= ")\n";
        }

        if (Schema::hasTable('kategori')) {
            $columns = Schema::getColumnListing('kategori');
            $schemaText .= "Table kategori (" . implode(', ', $columns) . ")\n";
        }

        return $schemaText;
    }

    private function formatResponse($answer, $sql = null, $debugInfo = [])
    {
        $response = [
            'status' => 'success',
            'answer' => $answer
        ];

        if ($sql && env('SHOW_SQL_IN_RESPONSE', false)) {
            $response['sql_debug'] = $sql;
        }

        if ($this->debugMode) {
            $response['debug'] = $debugInfo;
        }

        return response()->json($response);
    }

    private function askNvidia($prompt, $apiKey, $isSqlMode = false)
    {
        try {
            if ($this->debugMode) {
                Log::debug('Chatbot AI Request', [
                    'prompt_length' => strlen($prompt),
                    'is_sql_mode' => $isSqlMode
                ]);
            }

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . trim($apiKey),
                    'Accept' => 'application/json'
                ])
                ->timeout(30)
                ->post('https://integrate.api.nvidia.com/v1/chat/completions', [
                    'model' => 'deepseek-ai/deepseek-v3.1-terminus',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a precise AI assistant.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => $isSqlMode ? 0.1 : 0.7,
                    'top_p' => 0.7,
                    'max_tokens' => 2048
                ]);

            if ($response->failed()) {
                Log::error('Chatbot AI Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return "ERROR_AI";
            }

            $result = $response->json();
            return $result['choices'][0]['message']['content'] ?? 'ERROR_FORMAT';

        } catch (\Exception $e) {
            Log::error('Chatbot AI Connection Error', [
                'error' => $e->getMessage()
            ]);
            return 'ERROR_CONNECTION';
        }
    }

    private function getWhitelistedSchema()
    {
        $schemaText = "";

        foreach ($this->whitelistTables as $table) {
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $schemaText .= "Table $table (" . implode(', ', $columns) . ")\n";
            }
        }

        return $schemaText;
    }

    public function testConnection()
    {
        try {
            $result = DB::select('SELECT DATABASE() as database_name');
            $databaseName = $result[0]->database_name ?? 'unknown';
            $schema = $this->getDetailedSchema();

            return response()->json([
                'status' => 'success',
                'message' => "Terhubung ke database: $databaseName",
                'database' => $databaseName,
                'tables' => $this->whitelistTables,
                'schema' => $schema,
                'doctrine_installed' => class_exists('Doctrine\DBAL\Driver\AbstractMySQLDriver')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal koneksi database: ' . $e->getMessage()
            ], 500);
        }
    }
}