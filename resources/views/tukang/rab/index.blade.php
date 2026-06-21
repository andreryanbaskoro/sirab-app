@extends('layouts.app')

@section('title', 'Daftar Hasil RAB')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Data Rencana Anggaran Biaya (RAB)</div>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. RAB</th>
                                <th>Permintaan</th>
                                <th>Konsumen</th>
                                <th>Total Anggaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $key }}</td>
                                <td>{{ $item->nomor_rab }}</td>
                                <td>{{ $item->permintaan->nomor_permintaan ?? '-' }}</td>
                                <td>{{ $item->permintaan->konsumen->name ?? '-' }}</td>
                                <td>Rp {{ number_format($item->total_final, 0, ',', '.') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td>
                                    <a href="{{ route('tukang.rab.show', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data RAB.</td>
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
