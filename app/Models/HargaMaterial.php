<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'harga',
        'tanggal_berlaku',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'tanggal_berlaku' => 'date',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
