<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $featuredUsers = \App\Models\User::withCount(['peminjaman', 'komentar', 'bukuFavorit'])
            ->where('role', '!=', '1')
            ->havingRaw('(peminjaman_count + komentar_count + buku_favorit_count) >= 1')
            ->orderByRaw('(peminjaman_count + komentar_count + buku_favorit_count) DESC')
            ->take(6)
            ->get();

        $books = Buku::with('penulis')->latest()->get();
        $totalBuku = Buku::count();
        $totalPenulis = \App\Models\Penulis::count();
        $totalTag = \App\Models\Tag::count();
        $totalKategori = \App\Models\Kategori::count();
        $totalUser = \App\Models\User::count();

        return view('home', compact('books', 'featuredUsers', 'totalUser', 'totalBuku', 'totalPenulis', 'totalKategori', 'totalTag'));
    }
    public function notification()
    {
        $notifications = \DB::table('notifications')
            ->where('user_id', session('user.id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notification', compact('notifications'));
    }
    public function user_list(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->oldest()
            ->paginate(12);

        return view('user_list', compact('users'));
    }
}