<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user', 'id_buku', 'tanggal_pinjam', 
        'tanggal_jatuh_tempo', 'tanggal_kembali', 'status'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getSisaHariAttribute()
    {
        $deadline = Carbon::parse($this->tanggal_jatuh_tempo);
        return now()->diffInDays($deadline, false);
    }

    public function getIsTerlambatAttribute()
    {
        return now()->gt(Carbon::parse($this->tanggal_jatuh_tempo)) && $this->status !== 'dikembalikan';
    }
}