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

    @php 
        $rab->load(['details.pekerjaan.kategori', 'details.children']);
        $pekerjaans = $rab->details->where('jenis_item', 'pekerjaan');
        $orphanMaterials = $rab->details->where('jenis_item', 'material')->where('parent_id', null);
        $jasas = $rab->details->where('jenis_item', 'jasa_tukang');
        $tambahans = $rab->details->where('jenis_item', 'tambahan');

        $groupedPekerjaan = $pekerjaans->groupBy(function($item) {
            return $item->pekerjaan && $item->pekerjaan->kategori 
                ? $item->pekerjaan->kategori->nama_kategori 
                : 'Pekerjaan Umum / Lain-lain';
        });

        $romanNumerals = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII'];
        $mainIndex = 1;
    @endphp

    <h3 style="margin-bottom: 5px;">A. REKAPITULASI RENCANA ANGGARAN BIAYA</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">NO</th>
                <th>URAIAN KATEGORI</th>
                <th width="25%">TOTAL BIAYA (Rp)</th>
                <th width="15%">BOBOT (%)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $rekapNo = 1; 
                $grandTotal = $rab->total_final > 0 ? $rab->total_final : 1; 
            @endphp
            @foreach($groupedPekerjaan as $kategori => $items)
                @php 
                    $subTotalGroup = $items->sum('subtotal') + $items->sum(function($item) { return $item->children->sum('subtotal'); }); 
                    $bobot = ($subTotalGroup / $grandTotal) * 100;
                @endphp
                <tr>
                    <td class="text-center">{{ $romanNumerals[$rekapNo++] }}</td>
                    <td class="font-weight-bold">Pekerjaan {{ $kategori }}</td>
                    <td class="text-right">{{ number_format($subTotalGroup, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($bobot, 2) }}%</td>
                </tr>
            @endforeach
            
            @if($orphanMaterials->count() > 0)
                @php 
                    $subTotalGroup = $orphanMaterials->sum('subtotal');
                    $bobot = ($subTotalGroup / $grandTotal) * 100;
                @endphp
                <tr>
                    <td class="text-center">{{ $romanNumerals[$rekapNo++] }}</td>
                    <td class="font-weight-bold">Kebutuhan Material Tambahan / Umum</td>
                    <td class="text-right">{{ number_format($subTotalGroup, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($bobot, 2) }}%</td>
                </tr>
            @endif

            @if($jasas->count() > 0)
                @php 
                    $subTotalGroup = $jasas->sum('subtotal');
                    $bobot = ($subTotalGroup / $grandTotal) * 100;
                @endphp
                <tr>
                    <td class="text-center">{{ $romanNumerals[$rekapNo++] }}</td>
                    <td class="font-weight-bold">Jasa Kepala Tukang</td>
                    <td class="text-right">{{ number_format($subTotalGroup, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($bobot, 2) }}%</td>
                </tr>
            @endif

            @if($tambahans->count() > 0)
                @php 
                    $subTotalGroup = $tambahans->sum('subtotal');
                    $bobot = ($subTotalGroup / $grandTotal) * 100;
                @endphp
                <tr>
                    <td class="text-center">{{ $romanNumerals[$rekapNo++] }}</td>
                    <td class="font-weight-bold">Biaya Lain-lain / Tak Terduga</td>
                    <td class="text-right">{{ number_format($subTotalGroup, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($bobot, 2) }}%</td>
                </tr>
            @endif
        </tbody>
        <tfoot class="bg-light font-weight-bold">
            <tr>
                <td colspan="2" class="text-right">TOTAL SEBELUM PAJAK & PROFIT</td>
                <td class="text-right">Rp {{ number_format($rab->total_sebelum_pajak ?? ($rab->total_upah + $rab->total_material + $rab->biaya_jasa_tukang + $rab->biaya_tambahan), 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            @if(($rab->profit_nominal ?? 0) > 0)
            <tr>
                <td colspan="2" class="text-right">PROFIT / MARGIN ({{ (float)$rab->profit_persen }}%)</td>
                <td class="text-right">Rp {{ number_format($rab->profit_nominal, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            @endif
            @if(($rab->ppn_nominal ?? 0) > 0)
            <tr>
                <td colspan="2" class="text-right">PPN ({{ (float)$rab->ppn_persen }}%)</td>
                <td class="text-right">Rp {{ number_format($rab->ppn_nominal, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            @endif
            <tr>
                <td colspan="2" class="text-right" style="font-size:14px;">GRAND TOTAL</td>
                <td class="text-right" style="font-size:14px;">Rp {{ number_format($rab->total_final, 0, ',', '.') }}</td>
                <td class="text-center">100%</td>
            </tr>
        </tfoot>
    </table>

    <h3 style="margin-top: 30px; margin-bottom: 5px;">B. RINCIAN ANGGARAN BIAYA (RAB)</h3>
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
            @php $mainIndex = 1; @endphp
            
            @if($pekerjaans->count() > 0)
                @foreach($groupedPekerjaan as $kategori => $items)
                    <tr class="bg-light text-primary">
                        <td class="text-center font-weight-bold">{{ $romanNumerals[$mainIndex++] }}</td>
                        <td colspan="5" class="font-weight-bold">PEKERJAAN {{ strtoupper($kategori) }}</td>
                    </tr>
                    @php $no = 1; @endphp
                    @foreach($items as $pek)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="font-weight-bold" style="padding-left: 10px;">{{ $pek->nama_item }}</td>
                            <td class="text-center font-weight-bold">{{ (float)$pek->qty }}</td>
                            <td class="text-center font-weight-bold">{{ $pek->satuan }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($pek->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($pek->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @foreach($pek->children as $mat)
                        <tr>
                            <td></td>
                            <td style="padding-left: 30px;">- {{ $mat->nama_item }}</td>
                            <td class="text-center">{{ (float)$mat->qty }}</td>
                            <td class="text-center">{{ $mat->satuan }}</td>
                            <td class="text-right">{{ number_format($mat->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($mat->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                @endforeach
            @endif

            @if($orphanMaterials->count() > 0)
                <tr class="bg-light text-success">
                    <td class="text-center font-weight-bold">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold">MATERIAL UMUM / TAMBAHAN</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($orphanMaterials as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td style="padding-left: 10px;">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($jasas->count() > 0)
                <tr class="bg-light text-warning">
                    <td class="text-center font-weight-bold">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold">JASA KEPALA TUKANG</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($jasas as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td style="padding-left: 10px;">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($tambahans->count() > 0)
                <tr class="bg-light text-danger">
                    <td class="text-center font-weight-bold">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold">BIAYA LAIN-LAIN / TAMBAHAN</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($tambahans as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td style="padding-left: 10px;">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
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
