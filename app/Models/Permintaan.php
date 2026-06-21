<?php

namespace App\Models;

use App\Enums\PermintaanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permintaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_permintaan',
        'konsumen_id',
        'tukang_id',
        'tipe_rumah_id',
        'lokasi_proyek',
        'luas_bangunan',
        'catatan',
        'dokumen_path',
        'alasan_tolak',
        'status',
        'tanggal_permohonan',
        'jenis_jasa',
    ];

    protected function casts(): array
    {
        return [
            'status' => PermintaanStatus::class,
            'tanggal_permohonan' => 'date',
            'luas_bangunan' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->nomor_permintaan)) {
                $model->nomor_permintaan = self::generateNomor();
            }
        });
    }

    public static function generateNomor(): string
    {
        $prefix = 'PMT-' . now()->format('Ym') . '-';
        $last = self::withTrashed()->where('nomor_permintaan', 'like', $prefix . '%')
            ->orderBy('id', 'desc')->first();
        $seq = $last ? ((int) substr($last->nomor_permintaan, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    // ─── Relationships ───────────────────────────────────────────

    public function konsumen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'konsumen_id');
    }

    public function tukang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tukang_id');
    }

    public function tipeRumah(): BelongsTo
    {
        return $this->belongsTo(TipeRumah::class);
    }

    public function rab(): HasOne
    {
        return $this->hasOne(Rab::class);
    }

    public function kontrak(): HasOne
    {
        return $this->hasOne(Kontrak::class);
    }

    public function validasis(): HasMany
    {
        return $this->hasMany(Validasi::class);
    }

    // ─── Scopes ─────────────────────────────────────────────────

    public function scopeForKonsumen($query, $userId)
    {
        return $query->where('konsumen_id', $userId);
    }

    public function scopeForTukang($query, $userId)
    {
        return $query->where('tukang_id', $userId);
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
