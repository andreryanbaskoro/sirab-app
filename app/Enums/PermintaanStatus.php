<?php

namespace App\Enums;

enum PermintaanStatus: string
{
    case PENDING = 'pending';
    case DITERIMA_KONSULTAN = 'diterima_konsultan';
    case DITOLAK_KONSULTAN = 'ditolak_konsultan';
    case DISUSUN_RAB = 'disusun_rab';
    case MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';
    case DISETUJUI = 'disetujui';
    case DITOLAK_KONSUMEN = 'ditolak_konsumen';
    case KONTRAK_AKTIF = 'kontrak_aktif';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::DITERIMA_KONSULTAN => 'Diterima Konsultan',
            self::DITOLAK_KONSULTAN => 'Ditolak Konsultan',
            self::DISUSUN_RAB => 'Disusun RAB',
            self::MENUNGGU_PERSETUJUAN => 'Menunggu Persetujuan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK_KONSUMEN => 'Ditolak Konsumen',
            self::KONTRAK_AKTIF => 'Kontrak Aktif',
            self::SELESAI => 'Selesai',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'warning',
            self::DITERIMA_KONSULTAN, self::DISUSUN_RAB => 'info',
            self::MENUNGGU_PERSETUJUAN => 'primary',
            self::DISETUJUI, self::SELESAI, self::KONTRAK_AKTIF => 'success',
            self::DITOLAK_KONSULTAN, self::DITOLAK_KONSUMEN => 'danger',
        };
    }
}
