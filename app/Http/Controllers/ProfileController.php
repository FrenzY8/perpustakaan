<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $sessionUser = session('user');
        if (!$sessionUser) {
            return redirect('/login');
        }

        $user = DB::table('users')
            ->where('id', $sessionUser['id'])
            ->first();

        if (!$user) {
            session()->flush();
            return redirect('/login');
        }

        return view('dashboard/profile', compact('user'));
    }
    public function update(Request $request)
    {
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
    }
}