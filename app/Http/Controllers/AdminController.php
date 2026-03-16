<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        if (!session()->has('user') || session('user.role') != 1) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $user = DB::table('users')->where('id', session('user.id'))->first();

        $searchBook = request('search_book');
        $books = Buku::with('penulis', 'kategori')
            ->when($searchBook, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('penulis', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            })
            ->latest()->paginate(10);

        $searchUser = request('search_user');
        $users = User::when($searchUser, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()->get();

        $peminjaman = DB::table('peminjaman')
            ->join('users', 'peminjaman.id_user', '=', 'users.id')
            ->join('buku', 'peminjaman.id_buku', '=', 'buku.id')
            ->select('peminjaman.*', 'users.name as nama_user', 'buku.judul as judul_buku')
            ->latest('peminjaman.dibuat_pada')
            ->get();

        $authors = DB::table('penulis')->orderBy('nama', 'asc')->get();
        $stats = [
            'total' => DB::table('peminjaman')->count(),
            'dipinjam' => DB::table('peminjaman')->where('status', 'dipinjam')->count(),
            'terlambat' => DB::table('peminjaman')->where('status', 'terlambat')->count(),
            'kembali' => DB::table('peminjaman')->where('status', 'dikembalikan')->count(),
        ];

        $dendaUser = DB::table('peminjaman')
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
                $jatuhTempo = Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay();
                $hariIni = Carbon::now()->startOfDay();
                $p->hari_telat = $jatuhTempo->diffInDays($hariIni, false);
                $dendaAsli = 5000 + (($p->hari_telat - 1) * 2000);
                $p->total_tagihan = max(0, $dendaAsli - ($p->potongan_denda ?? 0));

                return $p;
            });

        $categories = DB::table('kategori')->orderBy('nama', 'asc')->get();

        return view('admin.panel', compact('user', 'books', 'dendaUser', 'peminjaman', 'stats', 'authors', 'categories', 'users'));
    }
    public function reset_denda($id)
    {
        DB::table('peminjaman')->where('id', $id)->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan'
        ]);

        return redirect()->back()->with('success', 'Buku telah kembali & denda dianggap lunas!');
    }
    public function potong_denda($id, Request $request)
    {
        $request->validate([
            'nominal_potongan' => 'required|numeric|min:0'
        ]);

        DB::table('peminjaman')
            ->where('id', $id)
            ->update([
                'potongan_denda' => $request->nominal_potongan,
                'diperbarui_pada' => now()
            ]);

        return redirect()->back()->with('success', 'Potongan denda berhasil diterapkan!');
    }
    public function kurangi_denda($id, Request $request)
    {
        $request->validate([
            'nominal_potongan' => 'required|numeric|min:0'
        ]);

        $pinjaman = Peminjaman::findOrFail($id);

        $pinjaman->potongan_denda = $request->nominal_potongan;
        $pinjaman->save();

        return back()->with('success', 'Denda berhasil dipotong!');
    }
    public function kembali($id)
    {
        DB::table('peminjaman')->where('id', $id)->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'diperbarui_pada' => now()
        ]);

        return back()->with('success', 'Buku telah berhasil dikembalikan!');
    }
    public function store_book(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'id_penulis' => 'required',
            'id_kategori' => 'required',
            'gambar_sampul' => 'nullable|url',
        ]);

        DB::table('buku')->insert([
            'judul' => $request->judul,
            'id_penulis' => $request->id_penulis,
            'id_kategori' => $request->id_kategori,
            'isbn' => $request->isbn,
            'price' => $request->price,
            'size' => $request->size,
            'jumlah_halaman' => $request->halaman,
            'ringkasan' => $request->ringkasan,
            'gambar_sampul' => $request->gambar_sampul ?? 'https://pngimg.com/uploads/book/book_PNG51090.png',
            'penerbit' => 'Jokopus Publishing',
            'format' => $request->pormat,
            'tanggal_terbit' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/panel')->with('success', 'Buku baru berhasil dipajang!');
    }
    public function update_book(Request $request)
    {
        try {
            DB::table('buku')->where('id', $request->id)->update([
                'judul' => $request->judul,
                'id_penulis' => $request->id_penulis,
                'id_kategori' => $request->id_kategori,
                'gambar_sampul' => $request->gambar_sampul,
                'isbn' => $request->isbn,
                'updated_at' => now(),
            ]);

            return redirect('/admin/panel')->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    public function update_role(Request $request)
    {
        try {
            if (!in_array($request->role, ['0', '1'])) {
                return redirect()->back()->with('error', 'Role harus Admin atau User!');
            }

            DB::table('users')->where('id', $request->id)->update([
                'role' => (int) $request->role,
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Role user berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
    public function delete_book($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::table('buku_favorit_user')->where('id_buku', $id)->delete();
                DB::table('buku_tag')->where('id_buku', $id)->delete();
                DB::table('buku')->where('id', $id)->delete();
            });

            return redirect('/admin/panel')->with('success', 'Buku & semua keterkaitannya berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    public function store_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'required' => ':attribute wajib diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => (int) $request->role,
            ]);

            return redirect()->back()->with('success', 'User ' . $request->name . ' berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Detail Error: ' . $e->getMessage());
        }
    }
    public function delete_user(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (auth()->id() == $user->id) {
                return redirect()->back()->with('error', 'Akun sendiri!');
            }

            $user->delete();

            return redirect()->back()->with('success', 'User ' . $user->name . ' udah dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus user.');
        }
    }
}