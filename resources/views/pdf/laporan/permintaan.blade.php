<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Permintaan</title>
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
        <h2>LAPORAN PERMINTAAN RAB</h2>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Filter: {{ request('tanggal_dari') ?? '-' }} s/d {{ request('tanggal_sampai') ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Nomor</th>
                <th width="15%">Konsumen</th>
                <th width="15%">Konsultan</th>
                <th width="10%">Tipe Rumah</th>
                <th width="8%">Luas (m2)</th>
                <th width="15%">Lokasi Proyek</th>
                <th width="12%">Status</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->nomor_permintaan }}</td>
                <td>{{ $item->konsumen->profile->nama_lengkap ?? $item->konsumen->name }}</td>
                <td>{{ $item->konsultan->profile->nama_lengkap ?? $item->konsultan->name }}</td>
                <td class="text-center">{{ $item->tipeRumah->nama_tipe ?? '-' }}</td>
                <td class="text-center">{{ $item->luas_bangunan }}</td>
                <td>{{ $item->lokasi_proyek }}</td>
                <td class="text-center">{{ $item->status->label() }}</td>
                <td class="text-center">{{ $item->tanggal_permohonan }}</td>
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

