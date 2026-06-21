<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanRabExport implements FromCollection, WithHeadings, WithMapping
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
            'Nomor RAB',
            'Nomor Permintaan',
            'Kepala Tukang',
            'Total Material',
            'Total Pekerjaan',
            'Jasa Tukang',
            'Biaya Tambahan',
            'Grand Total',
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
            $row->nomor_rab,
            $row->permintaan->nomor_permintaan ?? '-',
            $row->tukang->profile->nama_lengkap ?? $row->tukang->name,
            $row->total_material,
            $row->total_pekerjaan,
            $row->biaya_jasa_tukang,
            $row->biaya_tambahan,
            $row->grand_total,
            $row->status->label(),
            $row->created_at->format('d/m/Y'),
        ];
    }
}
