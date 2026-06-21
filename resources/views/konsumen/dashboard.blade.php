@extends('layouts.app')

@section('title', 'Dashboard Konsumen')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalPermintaan }}</h2>
                <div class="m-b-5">Total Permintaan</div><i class="ti-envelope widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-info color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $permintaanAktif }}</h2>
                <div class="m-b-5">Permintaan Aktif</div><i class="ti-bar-chart widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-warning color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalRab }}</h2>
                <div class="m-b-5">Total RAB Diterima</div><i class="ti-calculator widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-danger color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $totalKontrak }}</h2>
                <div class="m-b-5">Kontrak Kerja</div><i class="ti-handshake widget-stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Permintaan Terbaru</div>
                <div class="ibox-tools">
                    <a class="btn btn-primary btn-sm" href="{{ route('konsumen.permintaan.create') }}">
                        <i class="fa fa-plus"></i> Buat Permintaan
                    </a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Kepala Tukang</th>
                                <th>Tipe Rumah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permintaanTerbaru as $item)
                            <tr>
                                <td>{{ $item->nomor_permintaan }}</td>
                                <td>
                                    @if($item->tukang->profile && $item->tukang->profile->foto)
                                        <img class="img-circle mr-2" src="{{ asset('storage/' . $item->tukang->profile->foto) }}" width="30">
                                    @else
                                        <img class="img-circle mr-2" src="{{ asset('themes/assets/img/admin-avatar.png') }}" width="30">
                                    @endif
                                    {{ $item->tukang->name }}
                                </td>
                                <td>{{ $item->tipeRumah->nama_tipe }}</td>
                                <td>{{ $item->tanggal_permohonan->format('d M Y') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td>
                                    <a href="{{ route('konsumen.permintaan.show', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada permintaan RAB.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
