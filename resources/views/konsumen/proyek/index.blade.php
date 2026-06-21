@extends('layouts.app')

@section('title', 'Proyek Berjalan')

@section('content')
<div class="page-heading">
    <h1 class="page-title">Proyek Berjalan (Aktif)</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('konsumen.dashboard') }}"><i class="fa-solid fa-home"></i></a></li>
        <li class="breadcrumb-item active">Proyek Berjalan</li>
    </ol>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">Daftar Kontrak / Pekerjaan Fisik</div>
        </div>
        <div class="ibox-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>No Kontrak</th>
                            <th>Tukang</th>
                            <th>Tipe Rumah</th>
                            <th>Nilai Proyek</th>
                            <th>Target Selesai</th>
                            <th>Status Pembayaran</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kontraks as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><strong>{{ $item->nomor_kontrak }}</strong></td>
                            <td>{{ $item->tukang->name }}</td>
                            <td>{{ $item->permintaan->tipeRumah->nama_tipe }}</td>
                            <td>Rp {{ number_format($item->nilai_kontrak, 0, ',', '.') }}</td>
                            <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $totalDibayar = $item->pembayarans->where('status', 'diverifikasi')->sum('jumlah');
                                    $persen = $item->nilai_kontrak > 0 ? ($totalDibayar / $item->nilai_kontrak) * 100 : 0;
                                @endphp
                                <div class="progress mb-1" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small>Rp {{ number_format($totalDibayar, 0, ',', '.') }} ({{ round($persen, 1) }}%)</small>
                            </td>
                            <td>
                                <a href="{{ route('konsumen.proyek.show', $item->id) }}" class="btn btn-info btn-xs" title="Lihat & Bayar">
                                    <i class="fa fa-wallet"></i> Kelola
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Anda tidak memiliki proyek aktif saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $kontraks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function() {
        $('#datatable').DataTable({
            responsive: true,
            "paging": false,
            "info": false
        });
    });
</script>
@endpush
