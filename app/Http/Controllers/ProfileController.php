<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        return view('profile', compact('user'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/profile'), $filename);

            $user->profile_photo = asset('uploads/profile/' . $filename);
        }

        $user->save();

        return back()->with('success', 'Profile updated');
    }
}