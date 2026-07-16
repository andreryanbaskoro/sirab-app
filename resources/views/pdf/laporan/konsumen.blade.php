<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Konsumen</title>
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
        <h2>LAPORAN DATA KONSUMEN</h2>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Filter: {{ request('tanggal_dari') ?? '-' }} s/d {{ request('tanggal_sampai') ?? '-' }}</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Nama Lengkap</th>
                <th width="20%">Email</th>
                <th width="15%">No. Telepon</th>
                <th width="20%">Alamat</th>
                <th width="15%">Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->profile->nama_lengkap ?? $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td class="text-center">{{ $item->profile->no_hp ?? '-' }}</td>
                <td>{{ $item->profile->alamat ?? '-' }}</td>
                <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

