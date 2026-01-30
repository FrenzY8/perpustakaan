<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'buku_favorit_user';
    public $timestamps = false;

    protected $fillable = ['id_user', 'id_buku'];

    public function buku() {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}