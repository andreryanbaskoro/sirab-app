@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalKonsumen }}</h2>
                <div class="m-b-5">Total Konsumen</div><i class="ti-user widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-info color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalTukang }}</h2>
                <div class="m-b-5">Total Kepala Tukang</div><i class="ti-id-badge widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-warning color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalPermintaan }}</h2>
                <div class="m-b-5">Permintaan RAB</div><i class="ti-envelope widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-danger color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalKontrakAktif }}</h2>
                <div class="m-b-5">Kontrak Aktif</div><i class="ti-handshake widget-stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Data Permintaan Terbaru</div>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Konsumen</th>
                                <th>Kepala Tukang</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permintaanTerbaru as $item)
                            <tr>
                                <td><a href="{{ route('admin.permintaan.show', $item->id) }}">{{ $item->nomor_permintaan }}</a></td>
                                <td>{{ $item->konsumen->name }}</td>
                                <td>{{ $item->tukang->name }}</td>
                                <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Nilai Proyek (Kontrak)</div>
            </div>
            <div class="ibox-body text-center p-5">
                <i class="fa fa-money fa-4x text-success mb-3"></i>
                <h5 class="text-muted">Total Nilai Kontrak Aktif & Selesai</h5>
                <h2 class="font-strong text-success">Rp {{ number_format($nilaiTotalKontrak, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection
