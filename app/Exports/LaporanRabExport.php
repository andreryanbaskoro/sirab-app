<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanRabExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Permintaan',
            'Konsumen',
            'Konsultan',
            'Nilai RAB (Rp)',
            'Status Persetujuan',
            'Kontrak Kerja',
            'Status Proyek',
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
            $row->permintaan->konsumen->name ?? '-',
            $row->konsultan->name,
            $row->total_final,
            $row->status->label(),
            $row->kontrak->nomor_kontrak ?? '-',
            $row->kontrak ? $row->kontrak->status->label() : '-',
            $row->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();
        
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastCol . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }
}
