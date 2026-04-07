<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function google_callback()
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $googleDriver */
            $googleDriver = Socialite::driver('google');
            $googleUser = $googleDriver->stateless()->user();
            $user = DB::table('users')->where('email', $googleUser->getEmail())->first();
            $avatarUrl = $googleUser->getAvatar();
            $filename = null;

            if ($avatarUrl) {
                $imageContents = Http::withoutVerifying()->get($avatarUrl)->body();
                $filename = 'google_' . time() . '_' . Str::random(5) . '.jpg';
                Storage::disk('public')->put('avatars/' . $filename, $imageContents);
            }

            if (!$user) {
                $userId = DB::table('users')->insertGetId([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'profile_photo' => $filename,
                    'password' => bcrypt(Str::random(16)),
                    'role' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $user = DB::table('users')->where('id', $userId)->first();
            }

            session([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);

            DB::table('notifications')->insert([
                'user_id' => $user->id,
                'title' => 'Login Berhasil',
                'message' => $user->name . ', Kamu berhasil login lewat Google!',
                'link' => '/dashboard',
                'icon' => 'login',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            return redirect('/dashboard');

        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            dd($e);
        }
    }
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
                'otp_generated_at' => now(),
            ],
            'otp_code' => $otp,
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

        $userAgent = $request->header('User-Agent');
        $device = 'Unknown Device';

        if (str_contains($userAgent, 'Mobile')) {
            $device = 'Smartphone/Mobile';
        } elseif (str_contains($userAgent, 'Windows')) {
            $device = 'Windows PC';
        } elseif (str_contains($userAgent, 'Macintosh')) {
            $device = 'MacBook/iMac';
        } elseif (str_contains($userAgent, 'Linux')) {
            $device = 'Linux Device';
        }

        DB::table('notifications')->insert([
            'user_id' => $user->id,
            'title' => 'Login Berhasil',
            'message' => $user->name . ', Kamu baru saja login di device: ' . $device,
            'icon' => 'devices',
            'link' => '/dashboard',
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
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

        DB::table('notifications')->insert([
            'user_id' => $userId,
            'title' => 'Selamat Datang!',
            'message' => 'Halo, ' . $data['name'] . ', selamat datang di Jokopus, Ayo mulai jelajahi buku sekarang!',
            'icon' => 'waving_hand',
            'link' => '/buku',
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
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

        $otp = rand(100000, 999999);
        session([
            'otp_code' => $otp,
            'otp_generated_at' => now(),
            'otp_nonce' => Str::uuid()->toString(),
        ]);

        Mail::to($request->email ?? $request['email'])->send(new SendOtpMail($otp, $request->name));

        return back()->with('success', $data['email']);
    }
}