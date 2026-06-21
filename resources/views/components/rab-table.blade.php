@props(['rab'])

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

    $romanNumerals = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV'];
    $mainIndex = 1;
@endphp

<!-- REKAPITULASI -->
<div class="table-responsive mb-5">
    <h5 class="font-weight-bold text-primary mb-3"><i class="fa fa-list"></i> A. REKAPITULASI RENCANA ANGGARAN BIAYA</h5>
    <table class="table table-bordered table-sm">
        <thead class="bg-light">
            <tr>
                <th width="10%" class="text-center">NO</th>
                <th>URAIAN KATEGORI</th>
                <th width="25%" class="text-right">TOTAL BIAYA (Rp)</th>
                <th width="15%" class="text-center">BOBOT (%)</th>
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
            <tr style="border-top: 2px solid #ccc;">
                <td colspan="2" class="text-right text-muted">TOTAL (UPAH + MATERIAL + TAMBAHAN)</td>
                <td class="text-right text-muted">Rp {{ number_format($rab->total_sebelum_pajak ?? ($rab->total_upah + $rab->total_material + $rab->biaya_jasa_tukang + $rab->biaya_tambahan), 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right text-success">PROFIT / MARGIN ({{ (float)($rab->profit_persen ?? 0) }}%)</td>
                <td class="text-right text-success">Rp {{ number_format($rab->profit_nominal ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right text-danger">PPN ({{ (float)($rab->ppn_persen ?? 0) }}%)</td>
                <td class="text-right text-danger">Rp {{ number_format($rab->ppn_nominal ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">-</td>
            </tr>
            <tr class="bg-primary text-white" style="font-size:16px;">
                <td colspan="2" class="text-right font-weight-bold">GRAND TOTAL</td>
                <td class="text-right font-weight-bold">Rp {{ number_format($rab->total_final, 0, ',', '.') }}</td>
                <td class="text-center font-weight-bold">100%</td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- RINCIAN ANGGARAN BIAYA -->
<div class="table-responsive">
    <h5 class="font-weight-bold text-primary mb-3 mt-4"><i class="fa fa-th-list"></i> B. RINCIAN ANGGARAN BIAYA (RAB)</h5>
    <table class="table table-bordered table-sm">
        <thead class="bg-dark text-white">
            <tr>
                <th width="5%" class="text-center">NO</th>
                <th width="35%">URAIAN PEKERJAAN & MATERIAL</th>
                <th width="10%" class="text-center">VOL</th>
                <th width="10%" class="text-center">SAT</th>
                <th width="20%" class="text-right">HARGA SATUAN (Rp)</th>
                <th width="20%" class="text-right">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $mainIndex = 1; @endphp
            
            @if($pekerjaans->count() > 0)
                @foreach($groupedPekerjaan as $kategori => $items)
                    <tr class="bg-light">
                        <td class="font-weight-bold text-center" style="font-size: 14px;">{{ $romanNumerals[$mainIndex++] }}</td>
                        <td colspan="5" class="font-weight-bold text-primary" style="font-size: 14px;">PEKERJAAN {{ strtoupper($kategori) }}</td>
                    </tr>
                    @php $no = 1; @endphp
                    @foreach($items as $pek)
                        <tr style="background-color: #f8f9fa;">
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="font-weight-bold text-info">{{ $pek->nama_item }}</td>
                            <td class="text-center font-weight-bold">{{ (float)$pek->qty }}</td>
                            <td class="text-center text-muted font-weight-bold">{{ $pek->satuan }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($pek->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-right font-weight-bold">{{ number_format($pek->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($pek->children->count() > 0)
                            @foreach($pek->children as $mat)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td></td>
                                <td class="pl-4 text-muted"><i class="fa fa-level-up fa-rotate-90"></i> {{ $mat->nama_item }}</td>
                                <td class="text-center">{{ (float)$mat->qty }}</td>
                                <td class="text-center text-muted">{{ $mat->satuan }}</td>
                                <td class="text-right">{{ number_format($mat->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($mat->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            @endif

            @if($orphanMaterials->count() > 0)
                <tr class="bg-light">
                    <td class="font-weight-bold text-center" style="font-size: 14px;">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold text-success" style="font-size: 14px;">MATERIAL UMUM / TAMBAHAN</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($orphanMaterials as $detail)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="pl-3">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center text-muted">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($jasas->count() > 0)
                <tr class="bg-light">
                    <td class="font-weight-bold text-center" style="font-size: 14px;">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold text-warning" style="font-size: 14px;">JASA KEPALA TUKANG</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($jasas as $detail)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="pl-3">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center text-muted">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif

            @if($tambahans->count() > 0)
                <tr class="bg-light">
                    <td class="font-weight-bold text-center" style="font-size: 14px;">{{ $romanNumerals[$mainIndex++] }}</td>
                    <td colspan="5" class="font-weight-bold text-danger" style="font-size: 14px;">BIAYA LAIN-LAIN / TAK TERDUGA</td>
                </tr>
                @php $no = 1; @endphp
                @foreach($tambahans as $detail)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td class="text-center">{{ $no++ }}</td>
                        <td class="pl-3">{{ $detail->nama_item }}</td>
                        <td class="text-center">{{ (float)$detail->qty }}</td>
                        <td class="text-center text-muted">{{ $detail->satuan }}</td>
                        <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
