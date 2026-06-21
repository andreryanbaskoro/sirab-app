@extends('layouts.app')

@section('title', 'Detail RAB (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Detail RAB: {{ $rab->nomor_rab }}</div>
        <div class="ibox-tools">
            <a href="{{ route('admin.rab.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="ibox-body">
        <h4 class="mb-3">Status: <x-status-badge :status="$rab->status" /></h4>
        <div class="row">
            <div class="col-md-6">
                <strong>Konsumen:</strong> {{ $rab->permintaan->konsumen->name }}<br>
                <strong>Tukang:</strong> {{ $rab->tukang->name }}<br>
            </div>
            <div class="col-md-6 text-right">
                <h3 class="text-success">Total: Rp {{ number_format($rab->total_final, 0, ',', '.') }}</h3>
            </div>
        </div>
        
        <hr>
        <h5>Rincian Item</h5>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead style="border-bottom: 2px solid #333;">
                    <tr>
                        <th width="5%" class="text-center font-weight-bold">NO</th>
                        <th width="35%" class="font-weight-bold">URAIAN PEKERJAAN / MATERIAL</th>
                        <th width="10%" class="text-center font-weight-bold">VOL</th>
                        <th width="10%" class="text-center font-weight-bold">SAT</th>
                        <th width="20%" class="text-right font-weight-bold">HARGA SATUAN (Rp)</th>
                        <th width="20%" class="text-right font-weight-bold">JUMLAH (Rp)</th>
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

                        $romanNumerals = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                        $mainIndex = 1;
                    @endphp
                    
                    @if($pekerjaans->count() > 0)
                        <tr class="bg-light">
                            <td class="font-weight-bold text-center" style="font-size: 14px; padding-top: 15px;">{{ $romanNumerals[$mainIndex++] }}</td>
                            <td colspan="5" class="font-weight-bold text-primary" style="font-size: 14px; padding-top: 15px;">UPAH TENAGA KERJA</td>
                        </tr>
                        @php $katIndex = 'A'; @endphp
                        @foreach($groupedPekerjaan as $kategori => $items)
                            <tr>
                                <td class="font-weight-bold text-center" style="background-color: #fcfcfc;">{{ $katIndex++ }}</td>
                                <td colspan="5" class="font-weight-bold font-italic text-muted" style="background-color: #fcfcfc;">Pekerjaan {{ $kategori }}</td>
                            </tr>
                            @php $no = 1; @endphp
                            @foreach($items as $detail)
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="text-center text-muted">{{ $no++ }}</td>
                                    <td class="pl-4">{{ $detail->nama_item }}</td>
                                    <td class="text-center">{{ (float)$detail->qty }}</td>
                                    <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                    <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif

                    @if($materials->count() > 0)
                        <tr class="bg-light">
                            <td class="font-weight-bold text-center" style="font-size: 14px; padding-top: 15px;">{{ $romanNumerals[$mainIndex++] }}</td>
                            <td colspan="5" class="font-weight-bold text-success" style="font-size: 14px; padding-top: 15px;">KEBUTUHAN MATERIAL</td>
                        </tr>
                        @php $no = 1; @endphp
                        @foreach($materials as $detail)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td class="text-center text-muted">{{ $no++ }}</td>
                                <td class="pl-4">{{ $detail->nama_item }}</td>
                                <td class="text-center">{{ (float)$detail->qty }}</td>
                                <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if($jasas->count() > 0)
                        <tr class="bg-light">
                            <td class="font-weight-bold text-center" style="font-size: 14px; padding-top: 15px;">{{ $romanNumerals[$mainIndex++] }}</td>
                            <td colspan="5" class="font-weight-bold text-warning" style="font-size: 14px; padding-top: 15px;">JASA KEPALA TUKANG</td>
                        </tr>
                        @php $no = 1; @endphp
                        @foreach($jasas as $detail)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td class="text-center text-muted">{{ $no++ }}</td>
                                <td class="pl-4">{{ $detail->nama_item }}</td>
                                <td class="text-center">{{ (float)$detail->qty }}</td>
                                <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if($tambahans->count() > 0)
                        <tr class="bg-light">
                            <td class="font-weight-bold text-center" style="font-size: 14px; padding-top: 15px;">{{ $romanNumerals[$mainIndex++] }}</td>
                            <td colspan="5" class="font-weight-bold text-danger" style="font-size: 14px; padding-top: 15px;">BIAYA LAIN-LAIN / TAMBAHAN</td>
                        </tr>
                        @php $no = 1; @endphp
                        @foreach($tambahans as $detail)
                            <tr style="border-bottom: 1px solid #eee;">
                                <td class="text-center text-muted">{{ $no++ }}</td>
                                <td class="pl-4">{{ $detail->nama_item }}</td>
                                <td class="text-center">{{ (float)$detail->qty }}</td>
                                <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot style="border-top: 2px solid #333;">
                    <tr>
                        <td colspan="5" class="text-right text-muted pt-3">SUBTOTAL MATERIAL</td>
                        <td class="text-right pt-3">{{ number_format($rab->total_material, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right text-muted">SUBTOTAL UPAH PEKERJAAN</td>
                        <td class="text-right">{{ number_format($rab->total_upah, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right text-muted">BIAYA JASA KEPALA TUKANG</td>
                        <td class="text-right">{{ number_format($rab->biaya_jasa_tukang, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right text-muted pb-3">BIAYA TAK TERDUGA / TAMBAHAN</td>
                        <td class="text-right pb-3">{{ number_format($rab->biaya_tambahan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bg-primary text-white">
                        <td colspan="5" class="text-right font-weight-bold" style="font-size: 16px; padding: 15px;">GRAND TOTAL RENCANA ANGGARAN BIAYA</td>
                        <td class="text-right font-weight-bold" style="font-size: 16px; padding: 15px;">Rp {{ number_format($rab->total_final, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
