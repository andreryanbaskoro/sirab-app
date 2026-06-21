<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil RAB</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10px; color: #666; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 4px; }
        .data-table th { background-color: #f5f5f5; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL RAB</h1>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Filter: {{ request('tanggal_dari') ?? '-' }} s/d {{ request('tanggal_sampai') ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Nomor RAB</th>
                <th width="12%">Permintaan</th>
                <th width="15%">Kepala Tukang</th>
                <th width="12%">Material (Rp)</th>
                <th width="12%">Pekerjaan (Rp)</th>
                <th width="12%">Grand Total (Rp)</th>
                <th width="12%">Status</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->nomor_rab }}</td>
                <td class="text-center">{{ $item->permintaan->nomor_permintaan ?? '-' }}</td>
                <td>{{ $item->tukang->name }}</td>
                <td class="text-right">{{ number_format($item->total_material, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->total_upah, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->total_final, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->status->label() }}</td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
