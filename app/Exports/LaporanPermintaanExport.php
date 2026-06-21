<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPermintaanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Permintaan',
            'Konsumen',
            'Kepala Tukang',
            'Tipe Rumah',
            'Luas (m2)',
            'Lokasi Proyek',
            'Status',
            'Tanggal',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->nomor_permintaan,
            $row->konsumen->profile->nama_lengkap ?? $row->konsumen->name,
            $row->tukang->profile->nama_lengkap ?? $row->tukang->name,
            $row->tipeRumah->nama ?? '-',
            $row->luas_bangunan,
            $row->lokasi_proyek,
            $row->status->label(),
            $row->tanggal_permohonan,
        ];
    }
}
