<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| REGISTER PAGE
|--------------------------------------------------------------------------
*/
Route::get('/daftar', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'Database berhasil terhubung';
    } catch (\Exception $e) {
        $dbStatus = 'Database gagal terhubung';
    }

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
            'password' => Hash::make($request->password),
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

    return view('dashboard');
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

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors(['login' => 'Email atau password salah']);
    }

    session([
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
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