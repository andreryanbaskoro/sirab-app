<?php

namespace App\Models;

use App\Enums\RabStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rab extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_rab',
        'permintaan_id',
        'tukang_id',
        'jasa_tukang_id',
        'biaya_jasa_tukang',
        'biaya_tambahan',
        'total_sebelum_pajak',
        'profit_persen',
        'profit_nominal',
        'ppn_persen',
        'ppn_nominal',
        'total_material',
        'total_upah',
        'total_final',
        'catatan_tukang',
        'alasan_tolak',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => RabStatus::class,
            'biaya_jasa_tukang' => 'decimal:2',
            'biaya_tambahan' => 'decimal:2',
            'total_sebelum_pajak' => 'decimal:2',
            'profit_persen' => 'decimal:2',
            'profit_nominal' => 'decimal:2',
            'ppn_persen' => 'decimal:2',
            'ppn_nominal' => 'decimal:2',
            'total_material' => 'decimal:2',
            'total_upah' => 'decimal:2',
            'total_final' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->nomor_rab)) {
                $model->nomor_rab = self::generateNomor();
            }
        });
    }

    public static function generateNomor(): string
    {
        $prefix = 'RAB-' . now()->format('Ym') . '-';
        $last = self::withTrashed()->where('nomor_rab', 'like', $prefix . '%')
            ->orderBy('id', 'desc')->first();
        $seq = $last ? ((int) substr($last->nomor_rab, -5)) + 1 : 1;
        return $prefix . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }

    // ─── Relationships ───────────────────────────────────────────

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class);
    }

    public function tukang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tukang_id');
    }

    public function jasaTukang(): BelongsTo
    {
        return $this->belongsTo(HargaJasaTukang::class, 'jasa_tukang_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RabDetail::class);
    }

    public function kontrak(): HasOne
    {
        return $this->hasOne(Kontrak::class);
    }

    // Keep for backward compatibility
    public function rabDetails(): HasMany
    {
        return $this->hasMany(RabDetail::class);
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
