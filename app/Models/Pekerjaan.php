<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kategori_pekerjaan_id',
        'nama_pekerjaan',
        'satuan',
        'deskripsi',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function kategori()
    {
        return $this->belongsTo(KategoriPekerjaan::class, 'kategori_pekerjaan_id');
    }

    public function hargaPekerjaans(): HasMany
    {
        return $this->hasMany(HargaPekerjaan::class);
    }

    public function materials()
    {
        return $this->hasMany(PekerjaanMaterial::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function hargaTerbaru(): ?HargaPekerjaan
    {
        return $this->hargaPekerjaans()
            ->orderByDesc('tanggal_berlaku')
            ->first();
    }
}
