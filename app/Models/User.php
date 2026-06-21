<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status_aktif' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function hargaJasaTukangs(): HasMany
    {
        return $this->hasMany(HargaJasaTukang::class);
    }

    public function permintaanSebagaiKonsumen(): HasMany
    {
        return $this->hasMany(Permintaan::class, 'konsumen_id');
    }

    public function permintaanSebagaiTukang(): HasMany
    {
        return $this->hasMany(Permintaan::class, 'tukang_id');
    }

    public function kontrakSebagaiKonsumen(): HasMany
    {
        return $this->hasMany(Kontrak::class, 'konsumen_id');
    }

    public function kontrakSebagaiTukang(): HasMany
    {
        return $this->hasMany(Kontrak::class, 'tukang_id');
    }

    // ─── Accessors ──────────────────────────────────────────────

    public function getFotoProfilAttribute(): string
    {
        $foto = $this->profile?->foto;
        if ($foto && file_exists(public_path('storage/' . $foto))) {
            return asset('storage/' . $foto);
        }
        return asset('themes/assets/img/admin-avatar.png');
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin_pu' => 'Admin PU',
            'kepala_tukang' => 'Kepala Tukang',
            'konsumen' => 'Konsumen',
            default => ucfirst($this->role ?? '-'),
        };
    }

    // ─── Role Helpers ────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasRole('admin_pu');
    }

    public function isTukang(): bool
    {
        return $this->hasRole('kepala_tukang');
    }

    public function isKonsumen(): bool
    {
        return $this->hasRole('konsumen');
    }

    // ─── Scopes ──────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }
}
