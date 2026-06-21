@extends('layouts.app')

@section('title', 'Laporan ' . ucfirst(str_replace('_', ' ', $type)))

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Filter Laporan</div>
    </div>
    <div class="ibox-body">
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link {{ $type === 'permintaan' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['type' => 'permintaan']) }}">Laporan Permintaan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'rab' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['type' => 'rab']) }}">Laporan RAB</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'kontrak' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['type' => 'kontrak']) }}">Laporan Kontrak</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'konsumen' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['type' => 'konsumen']) }}">Laporan Konsumen</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type === 'kepala_tukang' ? 'active' : '' }}" href="{{ route('admin.laporan.index', ['type' => 'kepala_tukang']) }}">Laporan Kepala Tukang</a>
            </li>
        </ul>
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="row">
            <input type="hidden" name="type" value="{{ $type }}">
            
            <div class="col-md-3 mb-3">
                <label>Pencarian</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Ketik kata kunci...">
            </div>

            @if(in_array($type, ['permintaan', 'rab', 'kontrak']))
            <div class="col-md-3 mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="col-md-2 mb-3">
                <label>Tanggal Dari</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>

            <div class="col-md-2 mb-3">
                <label>Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>

            <div class="col-md-2 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Laporan {{ ucfirst(str_replace('_', ' ', $type)) }}</div>
        <div class="ibox-tools d-flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fa fa-print"></i> Print</button>
            <a href="{{ route('admin.laporan.export-pdf', request()->all()) }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> PDF</a>
            <a href="{{ route('admin.laporan.export-excel', request()->all()) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="ibox-body" id="print-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        @if($type === 'konsumen' || $type === 'kepala_tukang')
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Tanggal Daftar</th>
                        @elseif($type === 'permintaan')
                            <th>Nomor</th>
                            <th>Konsumen</th>
                            <th>Kepala Tukang</th>
                            <th>Tipe Rumah</th>
                            <th>Luas (m2)</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        @elseif($type === 'rab')
                            <th>Nomor RAB</th>
                            <th>Permintaan</th>
                            <th>Tukang</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        @elseif($type === 'kontrak')
                            <th>Nomor Kontrak</th>
                            <th>RAB</th>
                            <th>Konsumen</th>
                            <th>Tukang</th>
                            <th>Nilai Kontrak</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $key => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $key }}</td>
                        @if($type === 'konsumen' || $type === 'kepala_tukang')
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->profile->no_hp ?? '-' }}</td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        @elseif($type === 'permintaan')
                            <td>{{ $item->nomor_permintaan }}</td>
                            <td>{{ $item->konsumen->name }}</td>
                            <td>{{ $item->tukang->name }}</td>
                            <td>{{ $item->tipeRumah->nama_tipe ?? '-' }}</td>
                            <td>{{ $item->luas_bangunan }}</td>
                            <td><x-status-badge :status="$item->status" /></td>
                            <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                        @elseif($type === 'rab')
                            <td>{{ $item->nomor_rab }}</td>
                            <td>{{ $item->permintaan->nomor_permintaan ?? '-' }}</td>
                            <td>{{ $item->tukang->name }}</td>
                            <td>Rp {{ number_format($item->total_final, 0, ',', '.') }}</td>
                            <td><x-status-badge :status="$item->status" /></td>
                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        @elseif($type === 'kontrak')
                            <td>{{ $item->nomor_kontrak }}</td>
                            <td>{{ $item->rab->nomor_rab ?? '-' }}</td>
                            <td>{{ $item->konsumen->name }}</td>
                            <td>{{ $item->tukang->name }}</td>
                            <td>Rp {{ number_format($item->nilai_kontrak, 0, ',', '.') }}</td>
                            <td>{{ $item->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{{ $item->tanggal_selesai->format('d/m/Y') }}</td>
                            <td><x-status-badge :status="$item->status" /></td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3 d-print-none">
            {{ $data->links() }}
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #print-area, #print-area * {
            visibility: visible;
        }
        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .ibox-title {
            visibility: visible;
            text-align: center;
        }
        .d-print-none {
            display: none !important;
        }
    }
</style>
@endsection
