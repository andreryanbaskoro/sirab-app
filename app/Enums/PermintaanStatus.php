<?php

namespace App\Enums;

enum PermintaanStatus: string
{
    case PENDING = 'pending';
    case DITERIMA_TUKANG = 'diterima_tukang';
    case DITOLAK_TUKANG = 'ditolak_tukang';
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
            self::DITERIMA_TUKANG => 'Diterima Tukang',
            self::DITOLAK_TUKANG => 'Ditolak Tukang',
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
            self::DITERIMA_TUKANG, self::DISUSUN_RAB => 'info',
            self::MENUNGGU_PERSETUJUAN => 'primary',
            self::DISETUJUI, self::SELESAI, self::KONTRAK_AKTIF => 'success',
            self::DITOLAK_TUKANG, self::DITOLAK_KONSUMEN => 'danger',
        };
    }
}
