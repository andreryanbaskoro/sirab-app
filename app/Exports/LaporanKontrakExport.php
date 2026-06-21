<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKontrakExport implements FromCollection, WithHeadings, WithMapping
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
            'Nomor Kontrak',
            'Nomor RAB',
            'Konsumen',
            'Kepala Tukang',
            'Nilai Kontrak',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->nomor_kontrak,
            $row->rab->nomor_rab ?? '-',
            $row->konsumen->profile->nama_lengkap ?? $row->konsumen->name,
            $row->tukang->profile->nama_lengkap ?? $row->tukang->name,
            $row->nilai_kontrak,
            $row->tanggal_mulai,
            $row->tanggal_selesai,
            $row->status->label(),
        ];
    }
}
