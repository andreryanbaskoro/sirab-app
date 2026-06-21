@extends('layouts.app')

@section('title', 'Detail RAB Bangunan')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Status RAB</div>
            </div>
            <div class="ibox-body">
                <h4 class="font-strong mb-3">{{ $rab->nomor_rab }}</h4>
                <div class="mb-4">
                    <x-status-badge :status="$rab->status" />
                </div>
                
                @if($rab->status === \App\Enums\RabStatus::DRAFT)
                <div class="alert alert-info">
                    <p>RAB ini masih berupa draft. Pastikan semua hitungan sudah benar sebelum mengajukan ke konsumen.</p>
                    <form action="{{ route('tukang.rab.submit', $rab->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block btn-submit"><i class="fa fa-paper-plane"></i> Ajukan ke Konsumen</button>
                    </form>
                    <a href="{{ route('tukang.rab.create', $rab->permintaan_id) }}" class="btn btn-outline-primary btn-block mt-2"><i class="fa fa-pencil"></i> Edit Ulang Draft</a>
                </div>
                @endif
                
                @if($rab->status === \App\Enums\RabStatus::MENUNGGU_PERSETUJUAN)
                <div class="alert alert-warning">
                    <p>RAB telah diajukan dan sedang menunggu persetujuan dari Konsumen.</p>
                </div>
                @endif
                
                @if($rab->status === \App\Enums\RabStatus::DITOLAK)
                <div class="alert alert-danger">
                    <strong>RAB Ditolak Konsumen</strong><br>
                    <p>Alasan: {{ $rab->alasan_tolak ?? 'Tidak disebutkan.' }}</p>
                    <a href="{{ route('tukang.rab.create', $rab->permintaan_id) }}" class="btn btn-primary btn-block mt-3"><i class="fa fa-refresh"></i> Revisi RAB</a>
                </div>
                @endif
                
                @if($rab->status === \App\Enums\RabStatus::DISETUJUI)
                <div class="alert alert-success">
                    <strong>RAB Telah Disetujui!</strong><br>
                    <p>Konsumen telah menyetujui RAB ini. Kontrak kerja telah diterbitkan.</p>
                    @if($rab->permintaan->kontrak)
                    <a href="{{ route('tukang.permintaan.show', $rab->permintaan_id) }}" class="btn btn-primary btn-block mt-2"><i class="fa fa-file-text"></i> Lihat Kontrak</a>
                    @endif
                </div>
                @endif
                
                <h5 class="text-info mt-4 mb-3"><i class="fa fa-building"></i> Data Proyek</h5>
                <ul class="list-group list-group-divider list-group-full">
                    <li class="list-group-item">
                        <span class="font-weight-bold">Pemilik:</span><br>
                        {{ $rab->permintaan->konsumen->name }}
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold">Tipe Rumah:</span><br>
                        {{ $rab->permintaan->tipeRumah->nama_tipe }} ({{ $rab->permintaan->luas_bangunan }} m²)
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold">Lokasi:</span><br>
                        {{ $rab->permintaan->lokasi_proyek }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Rincian Anggaran Biaya (RAB)</div>
                <div class="ibox-tools">
                    @if(in_array($rab->status->value, ['disetujui']))
                        <a href="{{ route('tukang.rab.pdf', $rab->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i> Cetak PDF</a>
                    @endif
                </div>
            </div>
            <div class="ibox-body">
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
                                $currentCategory = ''; 
                                $no = 1; 
                                $subtotalCategory = 0;
                            @endphp
                            
                            @foreach($rab->details as $index => $detail)
                                @if($currentCategory != $detail->jenis_item)
                                    @if($currentCategory != '')
                                        <!-- Subtotal untuk kategori sebelumnya bisa diletakkan di sini jika diperlukan -->
                                    @endif
                                    
                                    <tr class="bg-light">
                                        <td colspan="6" class="font-weight-bold" style="font-size: 14px; padding-top: 15px;">
                                            @if($detail->jenis_item == 'material') I. KEBUTUHAN MATERIAL
                                            @elseif($detail->jenis_item == 'pekerjaan') II. UPAH TENAGA KERJA
                                            @elseif($detail->jenis_item == 'jasa_tukang') III. JASA KEPALA TUKANG
                                            @else IV. BIAYA LAIN-LAIN / TAMBAHAN @endif
                                        </td>
                                    </tr>
                                    @php $currentCategory = $detail->jenis_item; $no = 1; @endphp
                                @endif
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td class="text-center text-muted">{{ $no++ }}</td>
                                    <td>{{ $detail->nama_item }}</td>
                                    <td class="text-center">{{ (float)$detail->qty }}</td>
                                    <td class="text-center text-muted">{{ $detail->satuan }}</td>
                                    <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
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

                @if($rab->catatan_tukang)
                <div class="mt-4 p-3 bg-light border-left-info border-left-3">
                    <h6 class="font-weight-bold">Catatan untuk Konsumen:</h6>
                    <p class="mb-0">{{ $rab->catatan_tukang }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtns = document.querySelectorAll('.btn-submit');
        submitBtns.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Ajukan RAB?',
                    text: "Draft ini akan dikirim ke Konsumen untuk disetujui. Setelah diajukan Anda tidak dapat mengubahnya lagi sampai ada respon dari konsumen.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3498db',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Ya, Ajukan!',
                    cancelButtonText: 'Cek Ulang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
