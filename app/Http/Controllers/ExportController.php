<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Penulis;
use App\Models\Kategori;
use App\Models\Tag;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportPdf($type)
    {
        $data = [];
        $title = "";
        $view = "pdf.table";

        switch ($type) {
            case 'buku':
                $data = Buku::with(['penulis', 'kategori'])->get();
                $title = "Laporan Data Koleksi Buku";
                break;

            case 'user':
                $data = User::all();
                $title = "Laporan Data User";
                break;

            case 'penulis':
                $data = Penulis::withCount('buku')->get();
                $title = "Laporan Data Penulis";
                break;

            case 'kategori':
                $data = Kategori::withCount('buku')->get();
                $title = "Laporan Data Kategori";
                break;

            case 'tag':
                $data = Tag::withCount('buku')->get();
                $title = "Laporan Data Tagar";
                break;

            case 'denda':
                $data = DB::table('peminjaman')
                    ->join('users', 'peminjaman.id_user', '=', 'users.id')
                    ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
                    ->select(
                        'peminjaman.*',
                        'users.name as nama_member',
                        'users.email as email_member',
                        'buku.judul as judul_buku'
                    )
                    ->whereNull('peminjaman.tanggal_kembali')
                    ->where('peminjaman.tanggal_jatuh_tempo', '<', now())
                    ->get()
                    ->map(function ($p) {

                        $jatuhTempo = \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
                        $hariIni = \Carbon\Carbon::now()->startOfDay();

                        $p->hari_telat = $jatuhTempo->diffInDays($hariIni, false);

                        $dendaAsli = 5000 + (($p->hari_telat - 1) * 2000);

                        $p->total_tagihan = max(0, $dendaAsli - ($p->potongan_denda ?? 0));

                        return $p;
                    });

                $title = "Laporan Data Denda";
                break;

            case 'history_denda':

                $data = DB::table('history_denda')
                    ->join('users', 'history_denda.id_user', '=', 'users.id')
                    ->select(
                        'history_denda.*',
                        'users.name as nama_user'
                    )
                    ->orderByDesc('history_denda.id')
                    ->get();

                $title = "Laporan Riwayat Denda";
                break;

            case 'pinjaman':

                $data = DB::table('peminjaman')
                    ->join('users', 'peminjaman.id_user', '=', 'users.id')
                    ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
                    ->select(
                        'peminjaman.*',
                        'users.name as nama_user',
                        'buku.judul as judul_buku'
                    )
                    ->orderByDesc('peminjaman.id')
                    ->get();

                $title = "Laporan Data Peminjaman";
                break;

            default:
                return abort(404, "Tipe laporan tidak ditemukan");
        }

        $pdf = Pdf::loadView($view, [
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'name' => session('user.name'),
            'date' => now()->format('d F Y')
        ]);

        $fileName = $type . '_' . now()->format('YmdHis') . '_' . Str::random(4) . '.pdf';
        $filePath = 'exported/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        return redirect(asset('storage/' . $filePath));
    }
}