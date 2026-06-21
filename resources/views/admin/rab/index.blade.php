@extends('layouts.app')

@section('title', 'Daftar RAB (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Pemantauan RAB</div>
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. RAB</th>
                        <th>Konsumen</th>
                        <th>Tukang</th>
                        <th>Total Final</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->nomor_rab }}</td>
                        <td>{{ $item->permintaan->konsumen->name }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>Rp {{ number_format($item->total_final, 0, ',', '.') }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                        <td>
                            <a href="{{ route('admin.rab.show', $item->id) }}" class="btn btn-info btn-xs">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>
    </div>
</div>
@endsection
