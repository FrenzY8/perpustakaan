<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Komentar;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $isWishlisted = false;
        $userId = session('user.id');
        $search = $request->query('search');
        $category = $request->query('category');
        $format = $request->query('format');
        $sort = $request->query('sort');
        $penulis_filter = $request->query('penulis');

        $query = Buku::with(['penulis', 'kategori']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhereHas('penulis', function ($sub) use ($search) {
                        $sub->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($category && $category !== 'all') {
            $query->whereHas('kategori', function ($q) use ($category) {
                $q->where('nama', $category);
            });
        }

        if ($penulis_filter && $penulis_filter !== 'all') {
            $query->whereHas('penulis', function ($q) use ($penulis_filter) {
                $q->where('nama', $penulis_filter);
            });
        }

        if ($format && $format !== 'all') {
            $query->where('format', $format);
        }

        switch ($sort) {
            case 'year_new':
                $query->orderBy('tanggal_terbit', 'desc');
                break;

            case 'year_old':
                $query->orderBy('tanggal_terbit', 'asc');
                break;

            case 'author_asc':
                $query->join('penulis', 'buku.id_penulis', '=', 'penulis.id')
                    ->select('buku.*')
                    ->orderBy('penulis.nama', 'asc');
                break;

            case 'title_asc':
                $query->orderBy('judul', 'asc');
                break;

            case 'pages':
                $query->orderBy('jumlah_halaman', 'desc');
                break;

            case 'oldest':
                $query->oldest();
                break;

            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(6)->appends($request->query());
        $categories = DB::table('kategori')->get();
        $penulis = DB::table('penulis')->orderBy('nama', 'asc')->get();

        $userWishlists = [];
        if ($userId) {
            $userWishlists = Wishlist::where('id_user', $userId)
                ->pluck('id_buku')
                ->toArray();
        }

        return view('buku/buku', compact('books', 'categories', 'penulis', 'userWishlists'));
    }
    public function pinjam_old(Request $request, $id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $userId = session('user.id');

        $durasi = $request->input('durasi', 7);

        if ($durasi > 30)
            $durasi = 30;

        $cekPinjam = Peminjaman::where('id_user', $userId)
            ->where('id_buku', $id)
            ->where('status', 'dipinjam')
            ->first();

        if ($cekPinjam) {
            return back()->with('error', 'Kamu masih meminjam buku ini!');
        }

        Peminjaman::create([
            'id_user' => $userId,
            'id_buku' => $id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_jatuh_tempo' => Carbon::now()->addDays($durasi),
            'status' => 'dipinjam',
            'dibuat_pada' => Carbon::now(),
        ]);

        return back()->with('success', "Buku berhasil dipinjam selama $durasi hari!");
    }
    public function pinjam(Request $request, $id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $userId = session('user.id');
        $durasi = min($request->input('durasi', 7), 30);

        $cekPinjam = Peminjaman::where('id_user', $userId)
            ->where('id_buku', $id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        if ($cekPinjam) {
            return back()->with('error', 'Permintaan masih diproses atau buku sedang dipinjam!');
        }

        Peminjaman::create([
            'id_user' => $userId,
            'id_buku' => $id,
            'status' => 'menunggu',
            'tanggal_pinjam' => now(),
            'tanggal_jatuh_tempo' => now()->addDays($durasi),
            'dibuat_pada' => now(),
        ]);

        DB::table('notifications')->insert([
            'user_id' => session('user.id'),
            'title' => 'Permintaan Pinjaman',
            'message' => session('user.name') . ', Kamu berhasil meminta permintaan peminjaman buku selama <b>' + $durasi + '<b> hari, sekarang menunggu konfirmasi dari Admin',
            'link' => '/dashboard/pinjaman',
            'icon' => 'book',
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return back()->with('success', "Permintaan terkirim. Menunggu persetujuan admin.");
    }
    public function rating(Request $request, $id)
    {
        $userId = session('user.id');

        $existingRating = DB::table('ratings')
            ->where('user_id', $userId)
            ->where('buku_id', $id)
            ->first();

        if ($existingRating) {
            return back()->with('error', 'Kamu sudah memberikan penilaian untuk buku ini!');
        }

        DB::table('ratings')->insert([
            'user_id' => $userId,
            'buku_id' => $id,
            'skor' => $request->rating,
            'created_at' => now()
        ]);

        $averageRating = DB::table('ratings')
            ->where('buku_id', $id)
            ->avg('skor');

        DB::table('buku')->where('id', $id)->update([
            'rating' => $averageRating,
            'updated_at' => now()
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan!');
    }
    public function komentar(Request $request, $id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $request->validate([
            'isi_komentar' => 'required|min:3'
        ]);

        Komentar::create([
            'id_user' => session('user.id'),
            'id_buku' => $id,
            'isi_komentar' => $request->isi_komentar
        ]);

        return back()->with('success', 'Komentar berhasil di posting!');
    }
    public function komentar_create(Request $request, $id)
    {
        Komentar::create([
            'id_user' => session('user.id'),
            'id_buku' => $id,
            'isi_komentar' => $request->isi_komentar,
            'dibuat_pada' => now()
        ]);

        return back()->with('success', 'Komentar berhasil di posting!');
    }
    public function wishlist($id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $userId = session('user.id');

        $wishlist = Wishlist::where('id_user', $userId)->where('id_buku', $id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Dihapus dari wishlist.');
        } else {
            Wishlist::create(['id_user' => $userId, 'id_buku' => $id]);
            return back()->with('success', 'Berhasil masuk wishlist! (Di Favoritkan)');
        }
    }
    public function show($id)
    {
        $book = Buku::with(['penulis', 'komentar.user'])->findOrFail($id);

        $isWishlisted = false;
        $isCurrentlyBorrowing = false;
        $hasBorrowedBefore = false;

        if (session()->has('user')) {
            $userId = session('user.id');

            $users = User::where('id', '!=', $userId)->get();
            $isWishlisted = Wishlist::where('id_user', $userId)->where('id_buku', $id)->exists();

            $isCurrentlyBorrowing = Peminjaman::where('id_user', $userId)
                ->where('id_buku', $id)
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->exists();

            $hasBorrowedBefore = Peminjaman::where('id_user', $userId)
                ->where('id_buku', $id)
                ->where('status', 'dikembalikan')
                ->exists();
        }

        $suggestedBooks = Buku::with(relations: 'kategori')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        $wishlistCount = Wishlist::where('id_buku', $id)->count();

        return view('buku/detail', compact('book', 'isWishlisted', "suggestedBooks", 'wishlistCount', 'isCurrentlyBorrowing', 'hasBorrowedBefore'));
    }
}