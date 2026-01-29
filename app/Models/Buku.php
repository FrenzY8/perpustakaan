<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'gambar_sampul',
        'ringkasan',
        'penerbit',
        'isbn',
        'jumlah_halaman',
        'tanggal_terbit',
        'format',
        'id_penulis',
        'id_kategori',
    ];

    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'id_penulis');
    }
}
