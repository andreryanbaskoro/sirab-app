@extends('layouts.app')

@section('title', 'Laporan Hasil RAB')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Filter Laporan RAB</div>
    </div>
    <div class="ibox-body">
        <form action="{{ route('konsumen.laporan.index') }}" method="GET" class="row">
            <div class="col-md-3 mb-3">
                <label>Pencarian</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nomor RAB...">
            </div>

            <div class="col-md-3 mb-3">
                <label>Status Persetujuan</label>
                <select name="status" class="form-control">
                    <option value="">-- Semua Status --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

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
        <div class="ibox-title">Data Laporan Hasil RAB</div>
        <div class="ibox-tools d-flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fa fa-print"></i> Print</button>
            <a href="{{ route('konsumen.laporan.export-pdf', request()->all()) }}" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i> PDF</a>
            <a href="{{ route('konsumen.laporan.export-excel', request()->all()) }}" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
        </div>
    </div>
    <div class="ibox-body" id="print-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor RAB</th>
                        <th>Permintaan</th>
                        <th>Kepala Tukang</th>
                        <th>Nilai RAB (Rp)</th>
                        <th>Status Persetujuan</th>
                        <th>Kontrak Kerja</th>
                        <th>Status Proyek</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $key => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $key }}</td>
                        <td>{{ $item->nomor_rab }}</td>
                        <td>{{ $item->permintaan->nomor_permintaan ?? '-' }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>{{ number_format($item->total_final, 0, ',', '.') }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                        <td>{{ $item->kontrak->nomor_kontrak ?? '-' }}</td>
                        <td>
                            @if($item->kontrak)
                                <x-status-badge :status="$item->kontrak->status" />
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data ditemukan</td>
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
        table, th, td {
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }
        th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
    }
</style>
@endsection
