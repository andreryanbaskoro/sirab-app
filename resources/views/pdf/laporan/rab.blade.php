<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil RAB</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #000; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; font-weight: bold; }
        .header h2 { margin: 5px 0 0; font-size: 14px; text-transform: uppercase; font-weight: bold; }
        .header p { margin: 8px 0 0; font-size: 10px; color: #333; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; }
        .data-table th { background-color: #e0e0e0; text-align: center; font-weight: bold; text-transform: uppercase; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SISTEM INFORMASI RAB (SIRAB)</h1>
        <h2>LAPORAN HASIL RAB</h2>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Filter: {{ request('tanggal_dari') ?? '-' }} s/d {{ request('tanggal_sampai') ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Nomor RAB</th>
                <th width="12%">Permintaan</th>
                <th width="12%">Konsumen</th>
                <th width="12%">Kepala Tukang</th>
                <th width="12%">Nilai RAB (Rp)</th>
                <th width="10%">Status Persetujuan</th>
                <th width="12%">Kontrak Kerja</th>
                <th width="10%">Status Proyek</th>
                <th width="8%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->nomor_rab }}</td>
                <td class="text-center">{{ $item->permintaan->nomor_permintaan ?? '-' }}</td>
                <td>{{ $item->permintaan->konsumen->name ?? '-' }}</td>
                <td>{{ $item->tukang->name }}</td>
                <td class="text-right">{{ number_format($item->total_final, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->status->label() }}</td>
                <td class="text-center">{{ $item->kontrak->nomor_kontrak ?? '-' }}</td>
                <td class="text-center">{{ $item->kontrak ? $item->kontrak->status->label() : '-' }}</td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
