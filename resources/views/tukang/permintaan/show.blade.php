@extends('layouts.app')

@section('title', 'Detail Permintaan Masuk')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Aksi</div>
            </div>
            <div class="ibox-body">
                <h4 class="font-strong mb-3">{{ $permintaan->nomor_permintaan }}</h4>
                <div class="mb-4">
                    <x-status-badge :status="$permintaan->status" />
                </div>
                
                @if($permintaan->status === \App\Enums\PermintaanStatus::PENDING)
                <div class="alert alert-info">
                    <p>Permintaan baru dari konsumen. Anda memiliki waktu untuk meninjau detail proyek. Apakah Anda bersedia mengerjakan proyek ini?</p>
                    
                    <form action="{{ route('tukang.permintaan.terima', $permintaan->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block mb-2 btn-terima"><i class="fa fa-check"></i> Terima Proyek</button>
                    </form>
                    
                    <button type="button" class="btn btn-outline-danger btn-block" data-toggle="modal" data-target="#tolakModal"><i class="fa fa-times"></i> Tolak Proyek</button>
                </div>
                @endif
                
                @if($permintaan->status === \App\Enums\PermintaanStatus::DITERIMA_TUKANG || $permintaan->status === \App\Enums\PermintaanStatus::DISUSUN_RAB)
                <div class="alert alert-success">
                    <p>Anda telah menerima proyek ini. Langkah selanjutnya adalah menyusun dan mengajukan Rencana Anggaran Biaya (RAB).</p>
                    <a href="{{ route('tukang.rab.create', $permintaan->id) }}" class="btn btn-primary btn-block mt-3"><i class="fa fa-calculator"></i> Susun RAB Sekarang</a>
                </div>
                @endif
                
                <h5 class="text-info mt-4 mb-3"><i class="fa fa-user"></i> Data Konsumen</h5>
                <ul class="list-group list-group-divider list-group-full">
                    <li class="list-group-item">
                        <span class="font-weight-bold">Nama:</span><br>
                        {{ $permintaan->konsumen->name }}
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold">No. Telp / WA:</span><br>
                        {{ $permintaan->konsumen->profile->no_hp ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <span class="font-weight-bold">Alamat:</span><br>
                        {{ $permintaan->konsumen->profile->alamat ?? '-' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Detail Proyek Bangunan</div>
            </div>
            <div class="ibox-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="bg-light font-weight-bold">Tanggal Permintaan</td>
                            <td>{{ $permintaan->tanggal_permohonan->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Tipe Rumah</td>
                            <td>{{ $permintaan->tipeRumah->nama_tipe }} ({{ $permintaan->tipeRumah->deskripsi }})</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Jenis Jasa</td>
                            <td>{{ ucfirst($permintaan->jenis_jasa) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Luas Bangunan</td>
                            <td>{{ number_format($permintaan->luas_bangunan, 2, ',', '.') }} m²</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Lokasi Proyek</td>
                            <td>{{ $permintaan->lokasi_proyek }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Catatan Konsumen</td>
                            <td>{{ $permintaan->catatan ?? '-' }}</td>
                        </tr>
                        @if($permintaan->dokumen_path)
                        <tr>
                            <td class="bg-light font-weight-bold">Dokumen Lampiran</td>
                            <td>
                                <a href="{{ asset('storage/' . $permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="fa fa-download"></i> Unduh Dokumen (PDF/Gambar)
                                </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($permintaan->rab && $permintaan->status !== \App\Enums\PermintaanStatus::PENDING)
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Status RAB: {{ $permintaan->rab->nomor_rab }}</div>
                <div class="ibox-tools">
                    <a href="{{ route('tukang.rab.show', $permintaan->rab->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat RAB</a>
                </div>
            </div>
            <div class="ibox-body text-center p-4">
                <h3 class="font-strong">Total Anggaran: Rp {{ number_format($permintaan->rab->total_final, 0, ',', '.') }}</h3>
                <h5 class="mt-3"><x-status-badge :status="$permintaan->rab->status" /></h5>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('tukang.permintaan.tolak', $permintaan->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Permintaan Proyek</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_tolak" class="form-control" rows="4" required placeholder="Jelaskan alasan Anda menolak proyek ini (contoh: jadwal penuh, lokasi terlalu jauh, dsb)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const terimaBtn = document.querySelector('.btn-terima');
        if (terimaBtn) {
            terimaBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Terima Permintaan?',
                    text: "Anda akan menyanggupi untuk membuatkan RAB untuk proyek ini.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2ecc71',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Ya, Terima!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
