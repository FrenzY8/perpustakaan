<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('user')) {
            return redirect('/daftar');
        }

        $userId = session('user.id');

        $favGenre = DB::table('peminjaman')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->join('kategori', 'buku.id_kategori', '=', 'kategori.id') // Pakai 'kategori'
            ->where('peminjaman.id_user', $userId)
            ->select('kategori.nama', DB::raw('count(*) as total'))
            ->groupBy('kategori.nama')
            ->orderBy('total', 'desc')
            ->first();

        if (!$favGenre) {
            $favGenre = (object) ['nama' => 'Belum Ada', 'total' => 0];
        }

        $currentlyBorrowed = Peminjaman::with('buku.penulis')
            ->where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereNull('tanggal_kembali')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->take(3)
            ->get();

        $wishlist = Wishlist::with('buku.penulis')
            ->where('id_user', $userId)
            ->orderBy('dibuat_pada', 'desc')
            ->take(3)
            ->get();

        $totalPinjam = Peminjaman::where('id_user', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->count();

        $totalFavorit = Wishlist::where('id_user', $userId)
            ->count();

        $suggestedBooks = Buku::with('penulis')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard/dashboard', compact('currentlyBorrowed', 'suggestedBooks', 'favGenre', 'wishlist', 'totalPinjam', 'totalFavorit'));
    }
    public function pinjaman()
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $userId = session('user.id');

        $pinjaman = Peminjaman::with('buku.penulis')
            ->where('id_user', session('user.id'))
            ->whereNull('tanggal_kembali')
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        $wishlist = Wishlist::with(['buku.penulis'])
            ->where('id_user', $userId)
            ->get();

        $pinjamanIds = $pinjaman->pluck('id_buku')->toArray();

        $suggestedBooks = Buku::with('penulis')
            ->whereNotIn('id', $pinjamanIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard.pinjaman', compact('pinjaman', 'suggestedBooks'));
    }
    public function pinjaman_delete($id)
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $userId = session('user.id');

        $pinjaman = DB::table('peminjaman')
            ->where('id', $id)
            ->where('id_user', $userId)
            ->where('status', 'ditolak')
            ->first();

        if (!$pinjaman) {
            return back()->withErrors(['error' => 'Data tidak ditemukan atau tidak dapat dihapus.']);
        }

        DB::table('peminjaman')->where('id', $id)->delete();

        return back()->with('success', 'Riwayat pinjaman berhasil dihapus.');
    }
    public function kembalikan($id)
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }
        try {
            $pinjaman = Peminjaman::where('id', $id)
                ->where('id_user', session('user.id'))
                ->firstOrFail();

            $pinjaman->update([
                'tanggal_kembali' => now(),
                'status' => 'dikembalikan',
                'diperbarui_pada' => now()
            ]);

            return back()->with('success', 'Buku berhasil dikembalikan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan buku.');
        }
    }
    public function history()
    {
        $history = Peminjaman::with('buku.penulis')
            ->where('id_user', session('user.id'))
            ->where('status', 'dikembalikan')
            ->orderBy('tanggal_kembali', 'desc')
            ->get();

        $historyIds = $history->pluck('id_buku')->toArray();

        $suggestedBooks = Buku::with('penulis')
            ->whereNotIn('id', $historyIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard.history', compact('history', 'suggestedBooks'));
    }
    public function wishlist()
    {
        if (!session()->has('user')) {
            return redirect('/login');
        }

        $userId = session('user.id');

        $wishlist = Wishlist::with(['buku.penulis'])
            ->where('id_user', $userId)
            ->get();

        $wishlistIds = $wishlist->pluck('id_buku')->toArray();

        $suggestedBooks = Buku::with('penulis')
            ->whereNotIn('id', $wishlistIds)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('dashboard.wishlist', compact('wishlist', 'suggestedBooks'));
    }
    public function uang()
    {
        if (!session()->has('user')) {
            return redirect('/login')->with('error', 'Login dulu');
        }

        $userId = session('user.id');
        $user = User::find($userId);

        $listPinjaman = Peminjaman::with('buku')
            ->where('id_user', $userId)
            ->whereNull('tanggal_kembali')
            ->get();

        $bukuTelat = $listPinjaman->map(function ($p) {
            $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
            $hariIni = Carbon::now()->startOfDay();

            $selisih = $jatuhTempo->diffInDays($hariIni, false);
            $p->hari_telat = ($selisih > 0) ? (int) $selisih : 0;

            if ($p->hari_telat > 0) {
                $p->total_denda_item = 5000 + (($p->hari_telat - 1) * 2000);
            } else {
                $p->total_denda_item = 0;
            }

            return $p;
        })->where('hari_telat', '>', 0)->values();

        $dendaBersih = $bukuTelat->sum('total_denda_item') - Peminjaman::where('id_user', $userId)->whereNull('tanggal_kembali')->sum('potongan_denda');
        $totalTagihan = max(0, $dendaBersih);

        $totalHariTelat = $bukuTelat->sum('hari_telat');

        return view('dashboard.uang', compact('user', 'bukuTelat', 'totalTagihan', 'totalHariTelat'));
    }
}