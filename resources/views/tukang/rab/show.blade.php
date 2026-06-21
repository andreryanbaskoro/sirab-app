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
                    <form action="{{ route('tukang.rab.submit', $rab->id) }}" method="POST" class="mt-3" enctype="multipart/form-data">
                        @csrf
                        @if($rab->permintaan->sumber_denah === 'dibuatkan_tukang' && empty($rab->permintaan->dokumen_path))
                            <div class="form-group text-left border p-3 bg-white">
                                <label class="font-weight-bold">Upload Sketsa/Rancangan Denah Anda <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="dokumen_denah" required accept=".jpg,.jpeg,.png,.pdf">
                                <small class="text-muted"><i class="fa fa-info-circle"></i> Konsumen meminta Anda merancang denah. Upload file sketsa/rancangan Anda di sini agar konsumen bisa melihatnya saat menyetujui RAB.</small>
                                @error('dokumen_denah')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
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
                    <li class="list-group-item">
                        <span class="font-weight-bold">Sketsa / Denah:</span><br>
                        @if($rab->permintaan->dokumen_path)
                            @php
                                $ext = pathinfo($rab->permintaan->dokumen_path, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <div class="mt-2 text-center">
                                    <img src="{{ asset('storage/' . $rab->permintaan->dokumen_path) }}" class="img-fluid border rounded" style="max-height: 200px;" alt="Sketsa Denah">
                                </div>
                            @endif
                            <a href="{{ asset('storage/' . $rab->permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm btn-block mt-2"><i class="fa fa-eye"></i> Buka Layar Penuh</a>
                        @else
                            <span class="text-muted">Tidak ada dokumen</span>
                        @endif
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
                <x-rab-table :rab="$rab" />

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
                
                if (!form.reportValidity()) {
                    return;
                }
                
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
