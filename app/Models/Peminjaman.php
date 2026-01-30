<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    public $timestamps = false; 

    protected $fillable = [
        'id_user', 'id_buku', 'tanggal_pinjam', 
        'tanggal_jatuh_tempo', 'tanggal_kembali', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function buku() {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}