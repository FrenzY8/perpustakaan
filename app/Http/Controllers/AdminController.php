<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        if (!session()->has('user') || session('user.role') != 1) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $user = DB::table('users')->where('id', session('user.id'))->first();

        $trendLabels = [];
        $dataPinjam = [];
        $dataKembali = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = Carbon::now()->subDays($i)->isoFormat('D MMM');

            $dataPinjam[] = DB::table('peminjaman')
                ->whereDate('dibuat_pada', $date)
                ->count();

            $dataKembali[] = DB::table('peminjaman')
                ->whereDate('tanggal_kembali', $date)
                ->count();
        }

        $categoryData = DB::table('kategori')
            ->join('buku', 'kategori.id', '=', 'buku.id_kategori')
            ->select('kategori.nama', DB::raw('count(buku.id) as total'))
            ->groupBy('kategori.id', 'kategori.nama')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total_users' => DB::table('users')->count(),
            'total_books' => DB::table('buku')->count(),
            'dipinjam' => DB::table('peminjaman')->where('status', 'dipinjam')->count(),
            'terlambat' => DB::table('peminjaman')->where('status', 'terlambat')->count(),
            'kembali' => DB::table('peminjaman')->where('status', 'dikembalikan')->count(),
        ];

        $topAuthors = DB::table('penulis')
            ->join('buku', 'penulis.id', '=', 'buku.id_penulis')
            ->join('peminjaman', 'buku.id', '=', 'peminjaman.id_buku')
            ->select('penulis.nama', DB::raw('count(peminjaman.id) as total_pinjam'))
            ->groupBy('penulis.id', 'penulis.nama')
            ->orderBy('total_pinjam', 'desc')
            ->take(5)
            ->get();

        $ratingStats = DB::table('ratings')
            ->select('skor', DB::raw('count(*) as jumlah'))
            ->groupBy('skor')
            ->orderBy('skor', 'asc')
            ->get();

        $dataRating = [0, 0, 0, 0, 0];

        foreach ($ratingStats as $r) {
            if ($r->skor >= 1 && $r->skor <= 5) {
                $dataRating[$r->skor - 1] = $r->jumlah;
            }
        }

        $books = DB::table('buku')->get();
        $users = DB::table('users')->get();

        $semuaPinjaman = Peminjaman::whereNull('tanggal_kembali')->get();

        $dataDendaGrouped = $semuaPinjaman->map(function ($p) {
            $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();
            $selisih = $jatuhTempo->diffInDays($hariIni, false);
            $hariTelat = ($selisih > 0) ? (int) $selisih : 0;

            $denda = 0;
            if ($hariTelat > 0) {
                $denda = 5000 + (($hariTelat - 1) * 2000);
            }

            return [
                'tanggal' => $jatuhTempo->format('d M'),
                'denda' => $denda
            ];
        })->where('denda', '>', 0)
            ->groupBy('tanggal')
            ->map(fn($group) => $group->sum('denda'))
            ->take(7);

        $userDendaUnik = $semuaPinjaman->map(function ($p) {
            $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();
            $selisih = $jatuhTempo->diffInDays($hariIni, false);
            $hariTelat = ($selisih > 0) ? (int) $selisih : 0;

            $dendaKotor = ($hariTelat > 0) ? 5000 + (($hariTelat - 1) * 2000) : 0;
            $dendaBersih = max(0, $dendaKotor - ($p->potongan_denda ?? 0));

            return [
                'nama' => $p->user->name ?? 'Anonim',
                'foto_user' => $p->user->profile_photo,
                'denda' => $dendaBersih
            ];
        })
            ->groupBy('nama')
            ->map(function ($group) {
                $first = $group->first();

                return [
                    'nama' => $first['nama'],
                    'foto_user' => $first['foto_user'],
                    'total_denda' => $group->sum('denda')
                ];
            })
            ->filter(fn($user) => $user['total_denda'] > 0)
            ->sortByDesc('total_denda')
            ->take(3);

        $topThreeBooks = $semuaPinjaman->map(function ($p) {
            $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();
            $selisih = $jatuhTempo->diffInDays($hariIni, false);
            $hariTelat = ($selisih > 0) ? (int) $selisih : 0;

            $dendaKotor = ($hariTelat > 0) ? 5000 + (($hariTelat - 1) * 2000) : 0;
            $dendaBersih = max(0, $dendaKotor - ($p->potongan_denda ?? 0));

            return [
                'nama_peminjam' => $p->user->name ?? 'Anonim',
                'foto_user' => $p->user->profile_photo,
                'id_buku' => $p->buku->id,
                'judul_buku' => $p->buku->judul ?? 'Buku Terhapus',
                'gambar_sampul' => $p->buku->gambar_sampul,
                'total_denda' => $dendaBersih,
                'hari_telat' => $hariTelat
            ];
        })
            ->filter(fn($item) => $item['total_denda'] > 0)
            ->sortByDesc('total_denda')
            ->take(5);

        return view('admin.charts', compact('user', 'stats', 'books', 'users'))
            ->with([
                'ratingSeries' => json_encode($dataRating),
                'authorLabels' => json_encode($topAuthors->pluck('nama')),
                'authorSeries' => json_encode($topAuthors->pluck('total_pinjam')),
                'trendLabels' => json_encode($trendLabels),
                'trendPinjam' => json_encode($dataPinjam),
                'trendKembali' => json_encode($dataKembali),
                'catLabels' => json_encode($categoryData->pluck('nama')),
                'catSeries' => json_encode($categoryData->pluck('total')),
                'dendaLabels' => json_encode($dataDendaGrouped->keys()),
                'dendaSeries' => json_encode($dataDendaGrouped->values()),
                'topThreeUsers' => $userDendaUnik,
                'topThreeBooks' => $topThreeBooks,
            ]);
    }
    public function index_panel()
    {
        if (!session()->has('user') || session('user.role') != 1) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $user = DB::table('users')->where('id', session('user.id'))->first();

        $searchBook = request('search_book');
        $books = Buku::with('penulis', 'kategori')
            ->when($searchBook, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('penulis', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()->paginate(10);

        $categories = Kategori::when($searchBook, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $searchUser = request('search_user');
        $users = User::when($searchUser, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()->get();

        $peminjaman = DB::table('peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->select('peminjaman.*', 'users.name as nama_user', 'buku.judul as judul_buku')
            ->latest('peminjaman.dibuat_pada')
            ->get();

        $authors = DB::table('penulis')->orderBy('nama', 'asc')->get();
        $stats = [
            'total' => DB::table('peminjaman')->count(),
            'dipinjam' => DB::table('peminjaman')->where('status', 'dipinjam')->count(),
            'terlambat' => DB::table('peminjaman')->where('status', 'terlambat')->count(),
            'kembali' => DB::table('peminjaman')->where('status', 'dikembalikan')->count(),
            'menunggu' => DB::table('peminjaman')->where('status', 'menunggu')->count(),
            'ditolak' => DB::table('peminjaman')->where('status', 'ditolak')->count(),
        ];

        return view('admin.panel', compact('user', 'books', 'peminjaman', 'stats', 'authors', 'categories', 'users'));
    }
    public function index_pinjaman(Request $request)
    {
        if (!session()->has('user') || session('user.role') != 1) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $search = $request->input('search');
        $user = DB::table('users')->where('id', session('user.id'))->first();

        $books = DB::table('buku')->get();

        $month = $request->input('month');
        $year = $request->input('year');
        $status = $request->input('status');

        $peminjaman = DB::table('peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->select('peminjaman.*', 'users.name as nama_user', 'buku.judul as judul_buku')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('buku.judul', 'like', "%{$search}%")
                        ->orWhere('peminjaman.status', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('peminjaman.status', $status);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('peminjaman.tanggal_pinjam', $month);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('peminjaman.tanggal_pinjam', $year);
            })
            ->latest('peminjaman.dibuat_pada')
            ->paginate(10);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $peminjaman */
        $peminjaman->withQueryString();

        $authors = DB::table('penulis')->orderBy('nama', 'asc')->get();
        $stats = [
            'total' => DB::table('peminjaman')->count(),
            'dipinjam' => DB::table('peminjaman')->where('status', 'dipinjam')->count(),
            'terlambat' => DB::table('peminjaman')->where('status', 'terlambat')->count(),
            'kembali' => DB::table('peminjaman')->where('status', 'dikembalikan')->count(),
            'menunggu' => DB::table('peminjaman')->where('status', 'menunggu')->count(),
            'ditolak' => DB::table('peminjaman')->where('status', 'ditolak')->count(),
        ];

        $dendaUser = DB::table('peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->select(
                'peminjaman.*',
                'users.name as nama_member',
                'users.email as email_member',
                'buku.judul as judul_buku'
            )
            ->whereNull('peminjaman.tanggal_kembali')
            ->where('peminjaman.tanggal_jatuh_tempo', '<', now())
            ->get()
            ->map(function ($p) {
                $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $p->hari_telat = $jatuhTempo->diffInDays($hariIni, false);
                $dendaAsli = 5000 + (($p->hari_telat - 1) * 2000);
                $p->total_tagihan = max(0, $dendaAsli - ($p->potongan_denda ?? 0));

                return $p;
            });

        $categories = DB::table('kategori')->orderBy('nama', 'asc')->get();
        $pendingLoans = DB::table('peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->where('peminjaman.status', 'menunggu')
            ->select(
                'peminjaman.*',
                'users.name as nama_peminjam',
                'users.email as email_peminjam',
                'buku.judul as judul_buku',
                'buku.isbn as isbn_buku'
            )
            ->get();

        return view('admin.peminjaman', compact('user', 'books', 'pendingLoans', 'dendaUser', 'peminjaman', 'stats', 'authors', 'categories'));
    }
    public function addCategory(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategori,nama|max:255',
        ], [
            'nama.unique' => 'Nama kategori sudah ada!',
            'nama.required' => 'Nama kategori wajib diisi.'
        ]);

        try {
            Kategori::create([
                'nama' => $request->nama
            ]);

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambah kategori: ' . $e->getMessage());
        }
    }
    public function editCategory(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255|unique:kategori,nama,' . $id,
        ], [
            'nama.unique' => 'Nama kategori ini sudah ada!',
            'nama.required' => 'Nama kategori tidak boleh kosong.'
        ]);

        try {
            $category = Kategori::findOrFail($id);
            $category->update([
                'nama' => $request->nama
            ]);

            return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori.');
        }
    }
    public function deleteCategory($id)
    {
        try {
            $category = Kategori::findOrFail($id);
            \DB::table('buku')->where('id_kategori', $id)->update(['id_kategori' => null]);
            $category->delete();

            return redirect()->back()->with('success', 'Kategori dihapus dan buku terkait telah dilepas.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    public function terima_pinjaman($id)
    {
        if (session('user.role') !== '1') {
            return back()->with('error', 'Akses ditolak.');
        }

        try {
            $pinjaman = Peminjaman::with('buku')->where('id', $id)->where('status', 'menunggu')->firstOrFail();

            $durasiAsli = Carbon::parse($pinjaman->tanggal_pinjam)->diffInDays($pinjaman->tanggal_jatuh_tempo);

            $pinjaman->update([
                'status' => 'dipinjam',
                'id_admin' => session('user.id'),
                'tanggal_pinjam' => now(),
                'tanggal_jatuh_tempo' => now()->addDays($durasiAsli),
                'diperbarui_pada' => now()
            ]);

            DB::table('notifications')->insert([
                'user_id' => $pinjaman->id_user,
                'title' => 'Peminjaman Disetujui',
                'message' => 'Permintaan pinjam buku <b>' . ($pinjaman->buku->judul ?? 'Buku') . '</b> telah disetujui oleh Admin selama <b>' . $durasiAsli . ' hari</b>. Silakan ambil buku di perpustakaan!',
                'link' => '/dashboard/pinjaman',
                'icon' => 'auto_stories',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            return back()->with('success', 'Peminjaman disetujui! Durasi: ' . $durasiAsli . ' hari.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses data.');
        }
    }
    public function tolak_pinjaman($id)
    {
        if (session('user.role') !== '1') {
            return back()->with('error', 'Akses ditolak.');
        }

        try {
            $pinjaman = Peminjaman::with('buku')->where('id', $id)->where('status', 'menunggu')->firstOrFail();

            $pinjaman->update([
                'status' => 'ditolak',
                'id_admin' => session('user.id'),
                'diperbarui_pada' => now()
            ]);

            DB::table('notifications')->insert([
                'user_id' => $pinjaman->id_user,
                'title' => 'Peminjaman Ditolak',
                'message' => 'Maaf, permintaan pinjam buku <b>' . ($pinjaman->buku->judul ?? 'Buku') . '</b> ditolak oleh Admin. Silakan hubungi petugas untuk informasi lebih lanjut.',
                'link' => '/dashboard/pinjaman',
                'icon' => 'cancel',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            return back()->with('success', 'Peminjaman telah ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak peminjaman.');
        }
    }
    public function reset_denda($id)
    {
        $peminjaman = Peminjaman::with('buku', 'user')->find($id);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $jatuhTempo = Carbon::parse($peminjaman->tanggal_jatuh_tempo)->startOfDay();
        $hariIni = Carbon::now()->startOfDay();
        $selisih = $jatuhTempo->diffInDays($hariIni, false);

        $hariTelat = ($selisih > 0) ? (int) $selisih : 0;
        $nominalDenda = 0;

        if ($hariTelat > 0) {
            $nominalDenda = 5000 + (($hariTelat - 1) * 2000);
        }

        $peminjaman->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan'
        ]);

        $pdf = Pdf::loadView('pdf.invoice', [
            'peminjaman' => $peminjaman,
            'denda' => $nominalDenda,
            'hari_telat' => $hariTelat,
            'admin' => session('user.name')
        ]);

        $fileName = 'invoice_' . $id . '_' . time() . '.pdf';
        $filePath = 'invoice/' . $fileName;
        Storage::disk('public')->put('invoice/' . $fileName, $pdf->output());

        $urlInvoice = asset('storage/' . $filePath);
        $pesanInvoice = "[INVOICE_PDF] Halo, {$peminjaman->user->name}, Invoice denda '{$peminjaman->buku->judul}' telah di rilis: " . $urlInvoice;

        Message::create([
            'sender_id' => session('user.id'),
            'receiver_id' => $peminjaman->id_user,
            'message' => $pesanInvoice
        ]);

        Message::create([
            'sender_id' => session('user.id'),
            'receiver_id' => $peminjaman->id_user,
            'message' => "Halo, {$peminjaman->user->name}, Invoice denda '{$peminjaman->buku->judul}' telah di rilis."
        ]);

        DB::table('notifications')->insert([
            'user_id' => $peminjaman->id_user,
            'title' => $nominalDenda > 0 ? 'Buku Dikembalikan & Denda Lunas' : 'Buku Berhasil Dikembalikan',
            'message' => "Buku <b>{$peminjaman->buku->judul}</b> telah dikembalikan. " .
                ($nominalDenda > 0
                    ? "Denda sebesar <b>Rp " . number_format($nominalDenda, 0, ',', '.') . "</b> telah dinyatakan lunas."
                    : "Terima kasih telah mengembalikan buku tepat waktu!"),
            'link' => '/dashboard/pinjaman',
            'icon' => 'task_alt',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan. Denda Rp ' . number_format($nominalDenda, 0, ',', '.') . ' telah dilunasi.');
    }
    public function potong_denda($id, Request $request)
    {
        $request->validate([
            'nominal_potongan' => 'required|numeric|min:0'
        ]);

        $peminjaman = DB::table('peminjaman')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->where('peminjaman.id', $id)
            ->select('peminjaman.id_user', 'buku.judul')
            ->first();

        DB::table('notifications')->insert([
            'user_id' => $peminjaman->id_user,
            'title' => 'Potongan Denda Diberikan',
            'message' => "Admin memberikan potongan denda untuk buku <b>{$peminjaman->judul}</b> sebesar <b>Rp " . number_format($request->nominal_potongan, 0, ',', '.') . "</b>.",
            'link' => '/dashboard/pinjaman',
            'icon' => 'content_cut',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        DB::table('peminjaman')
            ->where('id', $id)
            ->update([
                'potongan_denda' => $request->nominal_potongan,
                'diperbarui_pada' => now()
            ]);

        return redirect()->back()->with('success', 'Potongan denda berhasil diterapkan!');
    }
    public function kembali($id)
    {
        DB::table('peminjaman')->where('id', $id)->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'diperbarui_pada' => now()
        ]);

        return back()->with('success', 'Buku telah berhasil dikembalikan!');
    }
    public function store_book(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_penulis' => 'required',
            'id_kategori' => 'required',
            'gambar_sampul' => 'nullable|url',
        ]);

        $finalPath = '';

        if ($request->hasFile('gambar_sampul_file')) {
            $path = $request->file('gambar_sampul_file')->store('sampul', 'public');
            $finalPath = "http://127.0.0.1:8000/storage/" . $path;
        } elseif ($request->filled('gambar_sampul_link')) {
            $finalPath = $request->gambar_sampul_link;
        }

        $cleanPrice = preg_replace('/[^0-9]/', '', $request->price);

        DB::table('buku')->insert([
            'judul' => $request->judul,
            'id_penulis' => $request->id_penulis,
            'id_kategori' => $request->id_kategori,
            'isbn' => $request->isbn ?? rand(1000, 9999),
            'price' => $cleanPrice,
            'size' => $request->size,
            'jumlah_halaman' => $request->halaman,
            'ringkasan' => $request->ringkasan,
            'gambar_sampul' => $finalPath,
            'penerbit' => 'Jokopus Publishing',
            'tanggal_terbit' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userIds = DB::table('users')->pluck('id');

        $notifications = [];
        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'title' => 'Koleksi Buku Baru!',
                'message' => "Buku baru <b>{$request->judul}</b> sekarang sudah tersedia. Yuk, jadi yang pertama meminjamnya!",
                'link' => '/buku',
                'icon' => 'library_add',
                'is_read' => 0,
                'created_at' => now(),
            ];
        }

        if (!empty($notifications)) {
            DB::table('notifications')->insert($notifications);
        }

        return redirect('/admin/panel')->with('success', 'Buku baru berhasil dipajang!');
    }
    public function update_book(Request $request)
    {
        $cleanPrice = preg_replace('/[^0-9]/', '', $request->price);
        try {
            $updateData = [
                'judul' => $request->judul,
                'id_penulis' => $request->id_penulis,
                'id_kategori' => $request->id_kategori,
                'price' => $cleanPrice,
                'updated_at' => now(),
            ];

            if ($request->hasFile('gambar_sampul_file')) {
                $path = $request->file('gambar_sampul_file')->store('sampul', 'public');
                $updateData['gambar_sampul'] = "http://127.0.0.1:8000/storage/" . $path;
            } else {
                $updateData['gambar_sampul'] = $request->gambar_sampul_link;
            }

            DB::table('buku')->where('id', $request->id)->update($updateData);

            return redirect('/admin/panel')->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    public function update_role(Request $request)
    {
        try {
            if (!in_array($request->role, ['0', '1'])) {
                return redirect()->back()->with('error', 'Role harus Admin atau User!');
            }

            DB::table('users')->where('id', $request->id)->update([
                'role' => (int) $request->role,
                'updated_at' => now(),
            ]);

            DB::table('notifications')->insert([
                'user_id' => $request->id,
                'title' => 'Status Akun Diperbarui',
                'message' => "Status akun kamu telah diubah oleh Admin menjadi: <b>" . ($request->role == '1' ? 'Admin' : 'Member') . "</b>.",
                'icon' => 'manage_accounts',
                'link' => '/dashboard',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Role user berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    public function delete_book($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::table('buku_favorit_user')->where('id_buku', $id)->delete();
                DB::table('buku_tag')->where('id_buku', $id)->delete();
                DB::table('buku')->where('id', $id)->delete();
            });

            return redirect('/admin/panel')->with('success', 'Buku & semua keterkaitannya berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    public function store_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'required' => ':attribute wajib diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => (int) $request->role,
            ]);

            return redirect()->back()->with('success', 'User ' . $request->name . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Detail Error: ' . $e->getMessage());
        }
    }
    public function delete_user(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (auth()->id() == $user->id) {
                return redirect()->back()->with('error', 'Akun sendiri!');
            }

            $user->delete();

            return redirect()->back()->with('success', 'User ' . $user->name . ' udah dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus user.');
        }
    }
}