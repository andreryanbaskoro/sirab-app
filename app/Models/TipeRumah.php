<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipeRumah extends Model
{
    use HasFactory;

    protected $table = 'tipe_rumahs';

    protected $fillable = [
        'nama_tipe',
        'luas',
        'deskripsi',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function permintaans(): HasMany
    {
        return $this->hasMany(Permintaan::class);
    }
}
