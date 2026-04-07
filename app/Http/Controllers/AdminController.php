<?php

namespace App\Http\Controllers;

use App\Models\Buku;
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

        $searchBook = request('search_book');
        $books = Buku::with('penulis', 'kategori')
            ->when($searchBook, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('penulis', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()->paginate(10);

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

        return view('admin.panel', compact('user', 'books', 'dendaUser', 'peminjaman', 'stats', 'authors', 'categories', 'users'));
    }
    public function index_pinjaman()
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

        return view('admin.peminjaman', compact('user', 'pendingLoans', 'books', 'dendaUser', 'peminjaman', 'stats', 'authors', 'categories', 'users'));
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

        DB::table('buku')->insert([
            'judul' => $request->judul,
            'id_penulis' => $request->id_penulis,
            'id_kategori' => $request->id_kategori,
            'isbn' => $request->isbn ?? rand(1000, 9999),
            'price' => $request->price,
            'size' => $request->size,
            'jumlah_halaman' => $request->halaman,
            'ringkasan' => $request->ringkasan,
            'gambar_sampul' => $request->gambar_sampul ?? 'https://pngimg.com/uploads/book/book_PNG51090.png',
            'penerbit' => 'Jokopus Publishing',
            'tanggal_terbit' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userIds = DB::table('users')->where('role', 0)->pluck('id');

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
        try {
            DB::table('buku')->where('id', $request->id)->update([
                'judul' => $request->judul,
                'id_penulis' => $request->id_penulis,
                'id_kategori' => $request->id_kategori,
                'gambar_sampul' => $request->gambar_sampul,
                'isbn' => $request->isbn,
                'updated_at' => now(),
            ]);

            return redirect('/admin/panel')->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
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