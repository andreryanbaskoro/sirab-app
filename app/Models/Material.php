<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_material',
        'satuan',
        'deskripsi',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function hargaMaterials(): HasMany
    {
        return $this->hasMany(HargaMaterial::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function hargaTerbaru(): ?HargaMaterial
    {
        return $this->hargaMaterials()
            ->orderByDesc('tanggal_berlaku')
            ->first();
    }
}
