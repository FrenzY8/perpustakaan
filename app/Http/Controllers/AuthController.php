<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    public function login_page()
    {
        if (session()->has('user')) {
            return redirect('/dashboard');
        }

        return view('auth/login');
    }
    public function daftar_page(Request $request)
    {
        $dbStatus = 'Database berhasil terhubung';
        return view('auth/daftar', compact('dbStatus'));
    }
    public function users_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $otp = rand(100000, 999999);
        session([
            'register_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'otp_code' => $otp,
                'otp_generated_at' => now(),
            ],
            'otp_generated_at' => now(),
            'otp_nonce' => Str::uuid()->toString(),
        ]);

        Mail::to($request->email ?? $request['email'])->send(new SendOtpMail($otp, $request->name));
        return redirect('/otp')->with('success', $request->email);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->first();

        if (!$user || $request->password != $user->password) {
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
    }
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
    public function otp(Request $request)
    {
        if (!session()->has('register_data')) {
            abort(403);
        }

        return view('auth/otp');
    }
    public function otp_verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $data = session('register_data');
        $storedOtp = session('otp_code');
        $generatedAt = session('otp_generated_at');

        if (!$data || !$generatedAt) {
            return redirect('/daftar')->withErrors('Session habis');
        }

        if (now()->diffInMinutes($generatedAt) >= 3) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa']);
        }

        if ($request->otp != $storedOtp) {
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
            'otp_nonce',
            'otp_code'
        ]);

        return redirect('/dashboard');
    }
    public function otp_resend(Request $request)
    {
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

        Http::withoutVerifying()->post('https://otp-service-beta.vercel.app/api/otp/generate', [
            'email' => $data['email'],
            'type' => 'numeric',
            'organization' => 'Jokopus',
            'subject' => 'Verifikasi Daftar Akun'
        ]);

        return back()->with('success', $data['email']);
    }
}