<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB - {{ $rab->nomor_rab }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { width: 120px; font-weight: bold; }
        .info-colon { width: 20px; text-align: center; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px; }
        .data-table th { background-color: #f5f5f5; text-align: center; font-weight: bold; }
        .data-table .text-center { text-align: center; }
        .data-table .text-right { text-align: right; }
        .data-table .font-weight-bold { font-weight: bold; }
        .data-table .bg-light { background-color: #fafafa; }
        .signature-table { width: 100%; margin-top: 40px; text-align: center; page-break-inside: avoid; }
        .signature-box { height: 80px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>RENCANA ANGGARAN BIAYA (RAB) BANGUNAN</h1>
        <p>Nomor Dokumen: {{ $rab->nomor_rab }} | Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Pemilik</td>
            <td class="info-colon">:</td>
            <td>{{ $rab->permintaan->konsumen->name }}</td>
            <td class="info-label">Tipe Bangunan</td>
            <td class="info-colon">:</td>
            <td>{{ $rab->permintaan->tipeRumah->nama_tipe }}</td>
        </tr>
        <tr>
            <td class="info-label">Lokasi Proyek</td>
            <td class="info-colon">:</td>
            <td>{{ $rab->permintaan->lokasi_proyek }}</td>
            <td class="info-label">Luas Bangunan</td>
            <td class="info-colon">:</td>
            <td>{{ number_format($rab->permintaan->luas_bangunan, 2, ',', '.') }} m²</td>
        </tr>
        <tr>
            <td class="info-label">Kepala Tukang</td>
            <td class="info-colon">:</td>
            <td>{{ $rab->permintaan->tukang->name }}</td>
            <td class="info-label">Status RAB</td>
            <td class="info-colon">:</td>
            <td><strong>{{ strtoupper(str_replace('_', ' ', $rab->status->value)) }}</strong></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="35%">URAIAN PEKERJAAN / MATERIAL</th>
                <th width="10%">VOL</th>
                <th width="10%">SAT</th>
                <th width="20%">HARGA SATUAN (Rp)</th>
                <th width="20%">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $rab->load('details.pekerjaan.kategori');
                $pekerjaans = $rab->details->where('jenis_item', 'pekerjaan');
                $materials = $rab->details->where('jenis_item', 'material');
                $jasas = $rab->details->where('jenis_item', 'jasa_tukang');
                $tambahans = $rab->details->where('jenis_item', 'tambahan');

                $groupedPekerjaan = $pekerjaans->groupBy(function($item) {
                    return $item->pekerjaan && $item->pekerjaan->kategori 
                        ? $item->pekerjaan->kategori->nama_kategori 
                        : 'Pekerjaan Umum / Lain-lain';
                });
            @endphp
            
            @if($pekerjaans->count() > 0)
                <tr>
                    <td colspan="6" class="font-weight-bold bg-light">I. UPAH TENAGA KERJA</td>
                </tr>
                @foreach($groupedPekerjaan as $kategori => $items)
                    <tr>
                        <td colspan="6" class="font-weight-bold" style="background-color: #fdfdfd; font-style: italic;">&nbsp;&nbsp;&nbsp;-- {{ $kategori }}</td>
                    </tr>
                    @php $no = 1; @endphp
                    @foreach($items as $detail)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td style="padding-left: 20px;">{{ $detail->nama_item }}</td>
                            <td class="text-center">{{ (float)$detail->qty }}</td>
                            <td class="text-center">{{ $detail->satuan }}</td>
                            <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @endif

            @if($materials->count() > 0)
                <tr>
                    <td colspan="6" class="font-weight-bold bg-light">II. KEBUTUHAN MATERIAL</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($materials as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($jasas->count() > 0)
                <tr>
                    <td colspan="6" class="font-weight-bold bg-light">III. JASA KEPALA TUKANG</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($jasas as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($tambahans->count() > 0)
                <tr>
                    <td colspan="6" class="font-weight-bold bg-light">IV. BIAYA LAIN-LAIN / TAMBAHAN</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($tambahans as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right font-weight-bold">SUBTOTAL MATERIAL</td>
                <td class="text-right font-weight-bold">{{ number_format($rab->total_material, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-weight-bold">SUBTOTAL UPAH PEKERJAAN</td>
                <td class="text-right font-weight-bold">{{ number_format($rab->total_upah, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-weight-bold">BIAYA JASA KEPALA TUKANG</td>
                <td class="text-right font-weight-bold">{{ number_format($rab->biaya_jasa_tukang, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-weight-bold">BIAYA TAK TERDUGA / TAMBAHAN</td>
                <td class="text-right font-weight-bold">{{ number_format($rab->biaya_tambahan, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #eee;">
                <td colspan="5" class="text-right font-weight-bold" style="font-size: 14px;">GRAND TOTAL BIAYA (RAB)</td>
                <td class="text-right font-weight-bold" style="font-size: 14px;">Rp {{ number_format($rab->total_final, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    @if($rab->catatan_tukang)
    <div style="margin-top: 20px;">
        <strong>Catatan/Keterangan Khusus:</strong>
        <p style="margin-top: 5px; font-style: italic;">{{ $rab->catatan_tukang }}</p>
    </div>
    @endif

    <table class="signature-table">
        <tr>
            <td width="50%">
                <p>Disetujui Oleh,<br><strong>Pemilik Proyek (Konsumen)</strong></p>
                <div class="signature-box"></div>
                <p><u>{{ $rab->permintaan->konsumen->name }}</u></p>
            </td>
            <td width="50%">
                <p>Dibuat Oleh,<br><strong>Kepala Tukang</strong></p>
                <div class="signature-box"></div>
                <p><u>{{ $rab->permintaan->tukang->name }}</u></p>
            </td>
        </tr>
    </table>

</body>
</html>
