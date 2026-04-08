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
    public function reset_page()
    {
        return view('auth/reset_password');
    }
    public function reset_form_page(Request $request)
    {
        if (!$request->has('token') || !$request->has('email')) {
            return redirect('/reset_password')->withErrors(['email' => 'Akses tidak sah.']);
        }

        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetData) {
            return redirect('/reset_password')->withErrors(['email' => 'Token tidak valid atau sudah digunakan.']);
        }

        $isExpired = now()->diffInMinutes($resetData->created_at) > 15;
        if ($isExpired) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect('/reset_password')->withErrors(['email' => 'Link reset sudah kedaluwarsa.']);
        }

        return view('auth/reset_password_form');
    }
    public function reset_password_process(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        $user = DB::table('users')->where('email', $request->email)->first();
        $resetLink = url("/reset-password-form?token=" . $token . "&email=" . urlencode($request->email));

        Mail::to($user->email)->send(new \App\Mail\SendPassReset($user->name, $resetLink));

        return back()->with('success', 'Link reset password telah dikirim ke email kamu.');
    }
    public function update_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $check = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$check) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        DB::table('users')->where('email', $request->email)->update([
            'password' => $request->password,
            'updated_at' => now()
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil diperbarui. Silakan login.');
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