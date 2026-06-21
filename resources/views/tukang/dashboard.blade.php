@extends('layouts.app')
@section('title', 'Dashboard Tukang')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-success color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $total_permintaan }}</h2>
                <div class="m-b-5">TOTAL PERMINTAAN</div><i class="ti-files widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-warning color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $permintaan_pending }}</h2>
                <div class="m-b-5">PERLU DIRESPON</div><i class="ti-bell widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-info color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $total_rab }}</h2>
                <div class="m-b-5">TOTAL RAB</div><i class="ti-pencil-alt widget-stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="ibox bg-danger color-white widget-stat">
            <div class="ibox-body">
                <h2 class="m-b-5 font-strong">{{ $kontrak_aktif }}</h2>
                <div class="m-b-5">KONTRAK AKTIF</div><i class="ti-briefcase widget-stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Permintaan Masuk Terbaru</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Konsumen</th>
                            <th>Tipe Rumah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaan_terbaru as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->konsumen->name ?? '-' }}</td>
                            <td>{{ $p->tipeRumah->nama_tipe ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $p->status->color() }}">{{ $p->status->label() }}</span>
                            </td>
                            <td>{{ $p->tanggal_permohonan->format('d M Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" data-original-title="Detail"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada permintaan masuk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
