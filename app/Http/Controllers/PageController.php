<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class PageController extends Controller
{
    public function home()
    {
        $books = Buku::with('penulis')->latest()->get();
        return view('home', compact('books'));
    }
}