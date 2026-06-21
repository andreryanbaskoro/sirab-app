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
                <x-rab-table :rab="$rab" />

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
