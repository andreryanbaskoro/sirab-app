<?php

namespace App\Models;

use App\Enums\KontrakStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kontrak extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_kontrak',
        'permintaan_id',
        'rab_id',
        'konsumen_id',
        'tukang_id',
        'nilai_kontrak',
        'tanggal_mulai',
        'tanggal_selesai',
        'file_kontrak',
        'keterangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => KontrakStatus::class,
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'nilai_kontrak' => 'decimal:2',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }

    public function konsumen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'konsumen_id');
    }

    public function tukang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tukang_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // ─── Accessors ──────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '-';
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status?->color() ?? 'secondary';
    }
}
