<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaJasaTukang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_jasa',
        'harga',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function tukang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
