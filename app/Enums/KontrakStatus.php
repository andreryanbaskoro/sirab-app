<?php

namespace App\Enums;

enum KontrakStatus: string
{
    case DRAFT = 'draft';
    case AKTIF = 'aktif';
    case SELESAI = 'selesai';
    case BATAL = 'batal';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::AKTIF => 'Aktif',
            self::SELESAI => 'Selesai',
            self::BATAL => 'Batal',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::AKTIF => 'primary',
            self::SELESAI => 'success',
            self::BATAL => 'danger',
        };
    }
}
