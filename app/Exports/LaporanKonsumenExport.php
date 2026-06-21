<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanKonsumenExport implements FromCollection, WithHeadings, WithMapping
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
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Alamat',
            'Tanggal Daftar',
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $row->profile->nama_lengkap ?? $row->name,
            $row->email,
            $row->profile->no_telepon ?? '-',
            $row->profile->alamat ?? '-',
            $row->created_at->format('d/m/Y H:i'),
        ];
    }
}
