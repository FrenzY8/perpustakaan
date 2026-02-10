<?php

/* 
 * Tugas Akhir
 * Rifky Fadillah
 * Peminjaman Buku
 * 04 Februari 2026
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Admin\BukuController;
use App\Models\Buku;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\Komentar;
use App\Models\User;
use Carbon\Carbon;

Route::get('/', [PageController::class, 'home']);

Route::post('/detail/{id}/komentar', function (Request $request, $id) {
    Komentar::create([
        'id_user' => session('user.id'),
        'id_buku' => $id,
        'isi_komentar' => $request->isi_komentar,
        'dibuat_pada' => now()
    ]);

    return back()->with('success', 'Komentar terkirim!');
});

Route::get('/detail/{id}', function ($id) {
    $book = Buku::with(['penulis', 'komentar.user'])->findOrFail($id);

    $isWishlisted = false;
    $isCurrentlyBorrowing = false;
    $hasBorrowedBefore = false;

    if (session()->has('user')) {
        $userId = session('user.id');

        $isWishlisted = Wishlist::where('id_user', $userId)->where('id_buku', $id)->exists();

        $isCurrentlyBorrowing = Peminjaman::where('id_user', $userId)
            ->where('id_buku', $id)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->exists();

        $hasBorrowedBefore = Peminjaman::where('id_user', $userId)
            ->where('id_buku', $id)
            ->where('status', 'dikembalikan')
            ->exists();
    }

    $suggestedBooks = Buku::with(relations: 'kategori')
        ->where('id', '!=', $id)
        ->inRandomOrder()
        ->limit(5)
        ->get();

    $wishlistCount = Wishlist::where('id_buku', $id)->count();

    return view('buku/detail', compact('book', 'isWishlisted', "suggestedBooks", 'wishlistCount', 'isCurrentlyBorrowing', 'hasBorrowedBefore'));
});

Route::post('/buku/{id}/komentar', function (Request $request, $id) {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $request->validate([
        'isi_komentar' => 'required|min:3'
    ]);

    Komentar::create([
        'id_user' => session('user.id'),
        'id_buku' => $id,
        'isi_komentar' => $request->isi_komentar
    ]);

    return back()->with('success', 'Komentar terkirim!');
});

Route::post('/wishlist/{id}', function ($id) {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $userId = session('user.id');

    $wishlist = Wishlist::where('id_user', $userId)->where('id_buku', $id)->first();

    if ($wishlist) {
        $wishlist->delete();
        return back()->with('success', 'Dihapus dari wishlist.');
    } else {
        Wishlist::create(['id_user' => $userId, 'id_buku' => $id]);
        return back()->with('success', 'Berhasil masuk wishlist!');
    }
});

Route::get('/buku', function (Request $request) {
    $search = $request->query('search');
    $category = $request->query('category');
    $format = $request->query('format');
    $sort = $request->query('sort');

    $query = Buku::with(['penulis', 'kategori']);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('judul', 'LIKE', "%{$search}%")
                ->orWhereHas('penulis', function ($sub) use ($search) {
                    $sub->where('nama', 'LIKE', "%{$search}%");
                });
        });
    }

    if ($category && $category !== 'all') {
        $query->whereHas('kategori', function ($q) use ($category) {
            $q->where('nama', $category);
        });
    }

    if ($format && $format !== 'all') {
        $query->where('format', $format);
    }

    switch ($sort) {
        case 'year_new':
            $query->orderBy('tanggal_terbit', 'desc');
            break;

        case 'year_old':
            $query->orderBy('tanggal_terbit', 'asc');
            break;

        case 'author_asc':
            $query->join('penulis', 'buku.id_penulis', '=', 'penulis.id')
                ->select('buku.*')
                ->orderBy('penulis.nama', 'asc');
            break;

        case 'title_asc':
            $query->orderBy('judul', 'asc');
            break;

        case 'pages':
            $query->orderBy('jumlah_halaman', 'desc');
            break;

        case 'oldest':
            $query->oldest();
            break;

        default:
            $query->latest();
            break;
    }

    $books = $query->paginate(6);
    $categories = DB::table('kategori')->get();

    return view('buku/buku', compact('books', 'categories'));
});

Route::post('/pinjam/{id}', function (Request $request, $id) {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $userId = session('user.id');

    $durasi = $request->input('durasi', 7);

    if ($durasi > 30)
        $durasi = 30;

    $cekPinjam = Peminjaman::where('id_user', $userId)
        ->where('id_buku', $id)
        ->where('status', 'dipinjam')
        ->first();

    if ($cekPinjam) {
        return back()->with('error', 'Kamu masih meminjam buku ini!');
    }

    Peminjaman::create([
        'id_user' => $userId,
        'id_buku' => $id,
        'tanggal_pinjam' => Carbon::now(),
        'tanggal_jatuh_tempo' => Carbon::now()->addDays($durasi),
        'status' => 'dipinjam',
        'dibuat_pada' => Carbon::now(),
    ]);

    return back()->with('success', "Buku berhasil dipinjam selama $durasi hari!");
});

Route::get('/dashboard/wishlist', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    $userId = session('user.id');

    $wishlist = Wishlist::with(['buku.penulis'])
        ->where('id_user', $userId)
        ->get();

    $wishlistIds = $wishlist->pluck('id_buku')->toArray();

    $suggestedBooks = Buku::with('penulis')
        ->whereNotIn('id', $wishlistIds)
        ->inRandomOrder()
        ->limit(5)
        ->get();

    return view('dashboard.wishlist', compact('wishlist', 'suggestedBooks'));
});

Route::get('/profile', function () {
    if (!session()->has('user'))
        return redirect('/login');

    $user = DB::table('users')->where('id', session('user.id'))->first();
    return view('dashboard/profile', compact('user'));
});

Route::get('/dashboard/uang', function () {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $userId = session('user.id');
    $user = User::find($userId);

    $listPinjaman = Peminjaman::with('buku')
        ->where('id_user', $userId)
        ->whereNull('tanggal_kembali')
        ->get();

    $bukuTelat = $listPinjaman->map(function ($p) {
        $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
        $hariIni = Carbon::now()->startOfDay();

        $selisih = $jatuhTempo->diffInDays($hariIni, false);
        $p->hari_telat = ($selisih > 0) ? (int) $selisih : 0;

        if ($p->hari_telat > 0) {
            $p->total_denda_item = 5000 + (($p->hari_telat - 1) * 2000);
        } else {
            $p->total_denda_item = 0;
        }

        return $p;
    })->where('hari_telat', '>', 0);

    $totalTagihan = $bukuTelat->sum('total_denda_item');
    $totalHariTelat = $bukuTelat->sum('hari_telat');

    return view('dashboard.uang', compact('user', 'bukuTelat', 'totalTagihan', 'totalHariTelat'));
});

Route::post('/admin/kurangi-denda/{id}', function (Request $request, $id) {
    $request->validate([
        'nominal_potongan' => 'required|numeric|min:0'
    ]);

    $pinjaman = Peminjaman::findOrFail($id);

    $pinjaman->potongan_denda = $request->nominal_potongan;
    $pinjaman->save();

    return back()->with('success', 'Denda berhasil dipotong!');
});

Route::get('/admin/panel', function () {
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
        ->latest()->get();

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
            $jatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = \Carbon\Carbon::now()->startOfDay();
            $p->hari_telat = $jatuhTempo->diffInDays($hariIni, false);
            $dendaAsli = 5000 + (($p->hari_telat - 1) * 2000);
            $p->total_tagihan = max(0, $dendaAsli - ($p->potongan_denda ?? 0));

            return $p;
        });

    $categories = DB::table('kategori')->orderBy('nama', 'asc')->get();

    return view('admin.panel', compact('user', 'books', 'dendaUser', 'peminjaman', 'stats', 'authors', 'categories', 'users'));
});

Route::post('/admin/denda/reset/{id}', function ($id) {
    DB::table('peminjaman')->where('id', $id)->update([
        'tanggal_kembali' => now(),
        'status' => 'dikembalikan'
    ]);

    return redirect()->back()->with('success', 'Buku telah kembali & denda dianggap lunas!');
});

Route::post('/admin/denda/potong/{id}', function (Request $request, $id) {
    $request->validate([
        'nominal_potongan' => 'required|numeric|min:0'
    ]);

    DB::table('peminjaman')
        ->where('id', $id)
        ->update([
            'potongan_denda' => $request->nominal_potongan,
            'diperbarui_pada' => now()
        ]);

    return redirect()->back()->with('success', 'Potongan denda berhasil diterapkan!');
});

Route::post('/admin/peminjaman/kembali/{id}', function ($id) {
    DB::table('peminjaman')->where('id', $id)->update([
        'status' => 'dikembalikan',
        'tanggal_kembali' => now(),
        'diperbarui_pada' => now()
    ]);

    return back()->with('success', 'Buku telah berhasil dikembalikan!');
});

Route::post('/admin/books/store', function (Request $request) {
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
        'isbn' => $request->isbn,
        'jumlah_halaman' => $request->halaman,
        'ringkasan' => $request->ringkasan,
        'gambar_sampul' => $request->gambar_sampul ?? 'https://via.placeholder.com/150',
        'penerbit' => 'Jokopus Publishing',
        'tanggal_terbit' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/admin/panel')->with('success', 'Buku baru berhasil dipajang!');
});

Route::post('/admin/books/update', function (Request $request) {
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
});

Route::post('/admin/users/update-role', function (Request $request) {
    try {
        if (!in_array($request->role, ['0', '1'])) {
            return redirect()->back()->with('error', 'Role harus Admin atau User!');
        }

        DB::table('users')->where('id', $request->id)->update([
            'role' => (int) $request->role,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Role user berhasil diperbarui!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
    }
});

Route::delete('/admin/books/delete/{id}', function ($id) {
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
});

Route::post('/profile/update', function (Request $request) {
    $userId = session('user.id');
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'password' => 'nullable|min:6|confirmed',
    ]);

    $updateData = [
        'name' => $request->name,
        'updated_at' => now(),
    ];

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '_' . $userId . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('avatars', $filename, 'public');
        $updateData['profile_photo'] = $filename;
    }

    if ($request->filled('password')) {
        $updateData['password'] = $request->password;
    }

    DB::table('users')->where('id', $userId)->update($updateData);
    session(['user.name' => $request->name]);

    return back()->with('success_update', true);
});

Route::get('/daftar', function () {
    $dbStatus = 'Database berhasil terhubung';

    return view('auth/daftar', compact('dbStatus'));
});

Route::get('/otp', function () {
    if (!session()->has('register_data')) {
        abort(403);
    }

    return view('auth/otp');
});

Route::post('/users/store', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    session([
        'register_data' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ],
        'otp_generated_at' => now(),
        'otp_nonce' => Str::uuid()->toString(),
    ]);

    Http::post('https://otp-service-beta.vercel.app/api/otp/generate', [
        'email' => $request->email,
        'type' => 'numeric',
        'organization' => 'Jokopus',
        'subject' => 'Verifikasi Daftar Akun'
    ]);

    return redirect('/otp')->with('success', $request->email);
});

Route::post('/admin/users/store', function (Request $request) {
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
            'password' => Hash::make($request->password),
            'role' => (int) $request->role,
        ]);

        return redirect()->back()->with('success', 'User ' . $request->name . ' berhasil ditambahkan!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Detail Error: ' . $e->getMessage());
    }
})->name('admin.users.store');

Route::delete('/admin/users/{id}', function ($id) {
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
})->name('admin.users.destroy');

Route::post('/otp/resend', function () {

    $data = session('register_data');
    if (!$data)
        abort(403);

    $lastSent = session('otp_generated_at');
    if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
        return back()->withErrors(['otp' => 'Tunggu 60 detik sebelum kirim ulang OTP']);
    }

    session([
        'otp_generated_at' => now(),
        'otp_nonce' => Str::uuid()->toString(),
    ]);

    Http::post('https://otp-service-beta.vercel.app/api/otp/generate', [
        'email' => $data['email'],
        'type' => 'numeric',
        'organization' => 'Jokopus',
        'subject' => 'Verifikasi Daftar Akun'
    ]);

    return back()->with('success', $data['email']);
});

Route::post('/otp/verify', function (Request $request) {

    $request->validate([
        'otp' => 'required'
    ]);

    $data = session('register_data');
    $generatedAt = session('otp_generated_at');

    if (!$data || !$generatedAt) {
        return redirect('/daftar')->withErrors('Session habis');
    }

    if (now()->diffInMinutes($generatedAt) >= 3) {
        return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
    }

    $response = Http::post('https://otp-service-beta.vercel.app/api/otp/verify', [
        'email' => $data['email'],
        'otp' => $request->otp
    ]);

    if (!$response->successful()) {
        return back()->withErrors(['otp' => 'OTP tidak valid']);
    }

    $userId = DB::table('users')->insertGetId([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'],
        'role' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    session([
        'user' => [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'],
        ]
    ]);

    session()->forget([
        'register_data',
        'otp_generated_at',
        'otp_nonce'
    ]);

    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    if (!session()->has('user')) {
        return redirect('/daftar');
    }

    $userId = session('user.id');

    $favGenre = DB::table('peminjaman')
        ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
        ->join('kategori', 'buku.id_kategori', '=', 'kategori.id') // Pakai 'kategori'
        ->where('peminjaman.id_user', $userId)
        ->select('kategori.nama', DB::raw('count(*) as total'))
        ->groupBy('kategori.nama')
        ->orderBy('total', 'desc')
        ->first();

    if (!$favGenre) {
        $favGenre = (object) ['nama' => 'Belum Ada', 'total' => 0];
    }

    $currentlyBorrowed = Peminjaman::with('buku.penulis')
        ->where('id_user', $userId)
        ->whereIn('status', ['dipinjam', 'terlambat'])
        ->whereNull('tanggal_kembali')
        ->orderBy('tanggal_jatuh_tempo', 'asc')
        ->take(3)
        ->get();

    $wishlist = Wishlist::with('buku.penulis')
        ->where('id_user', $userId)
        ->orderBy('dibuat_pada', 'desc')
        ->take(3)
        ->get();

    $totalPinjam = Peminjaman::where('id_user', $userId)
        ->whereIn('status', ['dipinjam', 'terlambat'])
        ->count();

    $totalFavorit = Wishlist::where('id_user', $userId)
        ->count();

    $suggestedBooks = Buku::with('penulis')
        ->inRandomOrder()
        ->limit(5)
        ->get();

    return view('dashboard/dashboard', compact('currentlyBorrowed', 'suggestedBooks', 'favGenre', 'wishlist', 'totalPinjam', 'totalFavorit'));
});

Route::get('/dashboard/pinjaman', function () {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $userId = session('user.id');

    $pinjaman = Peminjaman::with('buku.penulis')
        ->where('id_user', session('user.id'))
        ->whereNull('tanggal_kembali')
        ->orderBy('tanggal_jatuh_tempo', 'asc')
        ->get();

    $wishlist = Wishlist::with(['buku.penulis'])
        ->where('id_user', $userId)
        ->get();

    $pinjamanIds = $pinjaman->pluck('id_buku')->toArray();

    $suggestedBooks = Buku::with('penulis')
        ->whereNotIn('id', $pinjamanIds)
        ->inRandomOrder()
        ->limit(5)
        ->get();

    return view('dashboard.pinjaman', compact('pinjaman', 'suggestedBooks'));
});

Route::post('/dashboard/kembalikan/{id}', function ($id) {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }
    try {
        $pinjaman = Peminjaman::where('id', $id)
            ->where('id_user', session('user.id'))
            ->firstOrFail();

        $pinjaman->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
            'diperbarui_pada' => now()
        ]);

        return back()->with('success', 'Buku berhasil dikembalikan!');

    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengembalikan buku.');
    }
});

Route::get('/dashboard/history', function () {
    $history = Peminjaman::with('buku.penulis')
        ->where('id_user', session('user.id'))
        ->where('status', 'dikembalikan')
        ->orderBy('tanggal_kembali', 'desc')
        ->get();

    $historyIds = $history->pluck('id_buku')->toArray();

    $suggestedBooks = Buku::with('penulis')
        ->whereNotIn('id', $historyIds)
        ->inRandomOrder()
        ->limit(5)
        ->get();

    return view('dashboard.history', compact('history', 'suggestedBooks'));
});

Route::get('/login', function () {
    if (session()->has('user')) {
        return redirect('/dashboard');
    }

    return view('auth/login');
});

Route::post('/login', function (Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = DB::table('users')
        ->where('email', $request->email)
        ->first();

    if (!$user || !$request->password == $user->password) {
        return back()->withErrors(['login' => 'Email atau password salah']);
    }

    session([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);

    return redirect('/dashboard');
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});

Route::post('/delete-akun', function (Request $request) {
    $userId = session('user.id');

    if (!$userId) {
        return redirect('/')->with('error', 'Sesi tidak valid.');
    }

    $user = DB::table('users')->where('id', $userId)->first();

    if ($user) {
        try {
            DB::transaction(function () use ($userId, $user) {
                DB::table('buku_favorit_user')->where('id_user', $userId)->delete();
                DB::table('peminjaman')->where('id_user', $userId)->delete();
                DB::table('komentar_buku')->where('id_user', $userId)->delete();

                DB::table('users')->where('id', $userId)->delete();

                if ($user->profile_photo) {
                    $filePath = 'public/avatars/' . $user->profile_photo;
                    if (Storage::exists($filePath)) {
                        Storage::delete($filePath);
                    }
                }
            });

            $request->session()->flush();
            $request->session()->regenerate();

            return redirect('/')->with('success', 'Akun kamu telah berhasil dihapus secara permanen.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus akun.');
        }
    }

    return back()->with('error', 'Gagal menghapus akun. User tidak ditemukan.');
});