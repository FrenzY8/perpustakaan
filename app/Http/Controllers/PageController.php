<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class PageController extends Controller
{
    public function home()
    {
        $books = Buku::with('penulis')->latest()->get();
        $totalBuku = Buku::count();
        $totalPenulis = \App\Models\Penulis::count();

        return view('home', compact('books', 'totalBuku', 'totalPenulis'));
    }
}