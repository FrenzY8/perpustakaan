<?php

namespace App\Http\Controllers;
use App\Models\Buku;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Route;
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

/*
|--------------------------------------------------------------------------
| HOME - ETC
|--------------------------------------------------------------------------
*/
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

    $wishlistCount = Wishlist::where('id_buku', $id)->count();

    return view('detail', compact('book', 'isWishlisted', 'wishlistCount', 'isCurrentlyBorrowing', 'hasBorrowedBefore'));
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
    $query = Buku::with('penulis');
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('judul', 'LIKE', "%{$search}%")
              ->orWhereHas('penulis', function($sub) use ($search) {
                  $sub->where('nama', 'LIKE', "%{$search}%");
              });
        });
    }

    $books = $query->get();

    return view('buku', compact('books'));
});
Route::post('/pinjam/{id}', function ($id) {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }

    $userId = session('user.id');
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
        'tanggal_jatuh_tempo' => Carbon::now()->addDays(7), // Pinjam 7 hari
        'status' => 'dipinjam',
        'dibuat_pada' => Carbon::now(),
    ]);

    return back()->with('success', 'Buku berhasil dipinjam! Cek di dashboard kamu.');
});
/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/wishlist', function () {
    if (!session()->has('user')) {
        return redirect('/login');
    }

    $wishlist = Wishlist::with(['buku.penulis'])
                ->where('id_user', session('user.id'))
                ->get();

    return view('dashboard.wishlist', compact('wishlist'));
});
Route::get('/profile', function () {
    if (!session()->has('user')) return redirect('/login');
    
    $user = DB::table('users')->where('id', session('user.id'))->first();
    return view('profile', compact('user'));
});


Route::get('/admin/panel', function () {
    if (!session()->has('user') || session('user.role') != 1) {
        return redirect('/')->with('error', 'Akses ditolak!');
    }
    
    $user = DB::table('users')->where('id', session('user.id'))->first();
    
    $books = Buku::with('penulis', 'kategori')->latest()->get();
    $users = User::latest()->get();

    return view('admin.panel', compact('user', 'books', 'users'));
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
/*
|--------------------------------------------------------------------------
| REGISTER PAGE
|--------------------------------------------------------------------------
*/
Route::get('/daftar', function () {
    $dbStatus = 'Database berhasil terhubung';

    return view('daftar', compact('dbStatus'));
});

/*
|--------------------------------------------------------------------------
| OTP PAGE (BLOCK DIRECT ACCESS)
|--------------------------------------------------------------------------
*/
Route::get('/otp', function () {
    if (!session()->has('register_data')) {
        abort(403);
    }

    return view('otp');
});

/*
|--------------------------------------------------------------------------
| REGISTER SUBMIT (GENERATE OTP)
|--------------------------------------------------------------------------
*/
Route::post('/users/store', function (Request $request) {

    $request->validate([
        'name'     => 'required',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    session([
        'register_data' => [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
        ],
        'otp_generated_at' => now(),
        'otp_nonce'        => Str::uuid()->toString(),
    ]);

    Http::post('https://otp-service-beta.vercel.app/api/otp/generate', [
        'email'        => $request->email,
        'type'         => 'numeric',
        'organization' => 'Jokopus',
        'subject'      => 'Verifikasi Daftar Akun'
    ]);

    return redirect('/otp')->with('success', $request->email);
});

/*
|--------------------------------------------------------------------------
| RESEND OTP (COOLDOWN 60s)
|--------------------------------------------------------------------------
*/
Route::post('/otp/resend', function () {

    $data = session('register_data');
    if (!$data) abort(403);

    $lastSent = session('otp_generated_at');
    if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
        return back()->withErrors(['otp' => 'Tunggu 60 detik sebelum kirim ulang OTP']);
    }

    session([
        'otp_generated_at' => now(),
        'otp_nonce'        => Str::uuid()->toString(),
    ]);

    Http::post('https://otp-service-beta.vercel.app/api/otp/generate', [
        'email'        => $data['email'],
        'type'         => 'numeric',
        'organization' => 'Jokopus',
        'subject'      => 'Verifikasi Daftar Akun'
    ]);

    return back()->with('success', $data['email']);
});

/*
|--------------------------------------------------------------------------
| VERIFY OTP (EXPIRED 3 MENIT) + LOGIN SESSION
|--------------------------------------------------------------------------
*/
Route::post('/otp/verify', function (Request $request) {

    $request->validate([
        'otp' => 'required'
    ]);

    $data        = session('register_data');
    $generatedAt = session('otp_generated_at');

    if (!$data || !$generatedAt) {
        return redirect('/daftar')->withErrors('Session habis');
    }

    if (now()->diffInMinutes($generatedAt) >= 3) {
        return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
    }

    $response = Http::post('https://otp-service-beta.vercel.app/api/otp/verify', [
        'email' => $data['email'],
        'otp'   => $request->otp
    ]);

    if (!$response->successful()) {
        return back()->withErrors(['otp' => 'OTP tidak valid']);
    }

    $userId = DB::table('users')->insertGetId([
        'name'       => $data['name'],
        'email'      => $data['email'],
        'password'   => $data['password'],
        'role'       => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    session([
        'user' => [
            'id'    => $userId,
            'name'  => $data['name'],
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

/*
|--------------------------------------------------------------------------
| DASHBOARD (PROTECTED)
|--------------------------------------------------------------------------
*/
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
        $favGenre = (object)['nama' => 'Belum Ada', 'total' => 0];
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

    return view('dashboard', compact('currentlyBorrowed', 'favGenre', 'wishlist', 'totalPinjam', 'totalFavorit'));
});
Route::get('/dashboard/pinjaman', function () {
    if (!session()->has('user')) {
        return redirect('/login')->with('error', 'Login dulu');
    }
    $pinjaman = Peminjaman::with('buku.penulis')
        ->where('id_user', session('user.id'))
        ->whereNull('tanggal_kembali') // Hanya yang belum dikembalikan
        ->orderBy('tanggal_jatuh_tempo', 'asc')
        ->get();

    return view('dashboard.pinjaman', compact('pinjaman'));
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

    return view('dashboard.history', compact('history'));
});
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (session()->has('user')) {
        return redirect('/dashboard');
    }

    return view('login');
});

Route::post('/login', function (Request $request) {

    $request->validate([
        'email'    => 'required|email',
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
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);

    return redirect('/dashboard');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});