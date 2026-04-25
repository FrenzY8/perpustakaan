<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penulis extends Model
{
    protected $table = 'penulis';

    protected $fillable = [
        'nama',
    ];

    public $timestamps = false;

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_penulis');
    }
}