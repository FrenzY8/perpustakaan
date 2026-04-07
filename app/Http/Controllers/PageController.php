<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class PageController extends Controller
{
    public function home()
    {
        $books = Buku::with('penulis')->latest()->get();
        $totalBuku = Buku::count();
        $totalPenulis = \App\Models\Penulis::count();
        $totalTag = \App\Models\Tag::count();
        $totalKategori = \App\Models\Kategori::count();
        $totalUser = \App\Models\User::count();

        return view('home', compact('books', 'totalUser', 'totalBuku', 'totalPenulis', 'totalKategori', 'totalTag'));
    }
    public function notification()
    {
        $notifications = \DB::table('notifications')
            ->where('user_id', session('user.id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notification', compact('notifications'));
    }
}