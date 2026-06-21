<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPekerjaan extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori'];

    public function pekerjaans()
    {
        return $this->hasMany(Pekerjaan::class);
    }
}
