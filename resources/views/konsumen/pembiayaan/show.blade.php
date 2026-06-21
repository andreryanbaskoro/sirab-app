@extends('layouts.app')

@section('title', 'Detail Pembiayaan / RAB')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Detail RAB #{{ $rab->nomor_rab }}</div>
                <div class="ibox-tools">
                    <a href="{{ route('konsumen.pembiayaan.download-rab', $rab->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download PDF RAB</a>
                </div>
            </div>
            <div class="ibox-body">
                <table class="table table-bordered">
                        <thead style="border-bottom: 2px solid #333; background-color: #f8f9fa;">
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

                @if($rab->catatan_tukang)
                <div class="alert alert-info mt-3">
                    <strong>Catatan Tukang:</strong><br>
                    {{ $rab->catatan_tukang }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Informasi RAB</div>
            </div>
            <div class="ibox-body">
                <ul class="list-group list-group-divider list-group-full">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Status
                        <x-status-badge :status="$rab->status" />
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Kepala Tukang</small><br>
                        <strong>{{ $rab->tukang->name }}</strong>
                    </li>
                    <li class="list-group-item">
                        <small class="text-muted">Nomor Permintaan</small><br>
                        <a href="{{ route('konsumen.permintaan.show', $rab->permintaan_id) }}"><strong>{{ $rab->permintaan->nomor_permintaan }}</strong></a>
                    </li>
                    @if($rab->kontrak)
                    <li class="list-group-item">
                        <small class="text-muted">Nomor Kontrak</small><br>
                        <strong>{{ $rab->kontrak->nomor_kontrak }}</strong>
                        <a href="{{ route('konsumen.pembiayaan.download-kontrak', $rab->id) }}" class="btn btn-outline-primary btn-sm btn-block mt-2"><i class="fa fa-download"></i> Download Kontrak</a>
                    </li>
                    @endif
                </ul>

                @if($rab->status->value === 'menunggu_persetujuan')
                <div class="mt-4">
                    <form action="{{ route('konsumen.pembiayaan.setujui', $rab->id) }}" method="POST" id="form-approve">
                        @csrf
                        <button type="button" class="btn btn-success btn-block mb-2 btn-approve"><i class="fa fa-check"></i> Setujui RAB</button>
                    </form>
                    
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalTolak"><i class="fa fa-times"></i> Tolak RAB</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($rab->status->value === 'menunggu_persetujuan')
<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('konsumen.pembiayaan.tolak', $rab->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Alasan Penolakan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Berikan alasan mengapa RAB ini ditolak agar Kepala Tukang dapat merevisinya:</label>
                        <textarea name="alasan_tolak" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak RAB</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('.btn-approve').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Setujui RAB ini?',
        text: "Setelah disetujui, kontrak kerja akan otomatis terbuat.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-approve').submit();
        }
    })
});
</script>
@endif
@endsection
