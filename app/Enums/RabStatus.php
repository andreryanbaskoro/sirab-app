<?php

namespace App\Enums;

enum RabStatus: string
{
    case DRAFT = 'draft';
    case MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::MENUNGGU_PERSETUJUAN => 'Menunggu Persetujuan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::MENUNGGU_PERSETUJUAN => 'warning',
            self::DISETUJUI => 'success',
            self::DITOLAK => 'danger',
        };
    }
}
