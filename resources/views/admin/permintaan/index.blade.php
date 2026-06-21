@extends('layouts.app')

@section('title', 'Daftar Semua Permintaan (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Manajemen Permintaan Proyek</div>
    </div>
    <div class="ibox-body">
        <form method="GET" action="{{ route('admin.permintaan.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="No. Permintaan / Nama Konsumen" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label>Status</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                    <a href="{{ route('admin.permintaan.index') }}" class="btn btn-default">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Permintaan</th>
                        <th>Konsumen</th>
                        <th>Kepala Tukang</th>
                        <th>Tipe Rumah</th>
                        <th>Jenis Jasa</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->nomor_permintaan }}</td>
                        <td>{{ $item->konsumen->name }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>{{ $item->tipeRumah->nama_tipe }}</td>
                        <td>{{ ucfirst($item->jenis_jasa) }}</td>
                        <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                        <td>
                            <a href="{{ route('admin.permintaan.show', $item->id) }}" class="btn btn-info btn-xs">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
