<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    public $timestamps = false;
    protected $fillable = ['nama'];

    public function buku()
    {
        return $this->belongsToMany(Buku::class, 'buku_tag', 'id_tag', 'id_buku');
    }
}