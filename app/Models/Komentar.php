<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar_buku';
    public $timestamps = false;
    protected $fillable = ['id_user', 'id_buku', 'isi_komentar'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}