<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RabDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rab_id',
        'parent_id',
        'jenis_item',
        'referensi_id',
        'nama_item',
        'qty',
        'satuan',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:2',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Pekerjaan::class, 'referensi_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'referensi_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(RabDetail::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(RabDetail::class, 'parent_id');
    }
}
