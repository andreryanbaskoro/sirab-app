@extends('layouts.app')

@section('title', 'Permintaan Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Daftar Permintaan Konsumen</div>
            </div>
            <div class="ibox-body">
                <form method="GET" action="{{ route('tukang.permintaan.index') }}" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label>Filter Status</label>
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Permintaan</th>
                                <th>Nama Konsumen</th>
                                <th>Lokasi Proyek</th>
                                <th>Tipe Rumah</th>
                                <th>Jenis Jasa</th>
                                <th>Tanggal Masuk</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $key }}</td>
                                <td>{{ $item->nomor_permintaan }}</td>
                                <td>{{ $item->konsumen->name }}</td>
                                <td>{{ $item->lokasi_proyek }}</td>
                                <td>{{ $item->tipeRumah->nama_tipe ?? '-' }}</td>
                                <td>{{ ucfirst($item->jenis_jasa) }}</td>
                                <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td>
                                    <a href="{{ route('tukang.permintaan.show', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada permintaan masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
