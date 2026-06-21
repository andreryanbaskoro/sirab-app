@extends('layouts.app')

@section('title', 'Pembiayaan / Hasil RAB')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Daftar RAB Anda</div>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor RAB</th>
                                <th>Permintaan</th>
                                <th>Kepala Tukang</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $key }}</td>
                                <td>{{ $item->nomor_rab }}</td>
                                <td><a href="{{ route('konsumen.permintaan.show', $item->permintaan_id) }}">{{ $item->permintaan->nomor_permintaan }}</a></td>
                                <td>{{ $item->tukang->name }}</td>
                                <td>Rp {{ number_format($item->total_final, 0, ',', '.') }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('konsumen.pembiayaan.show', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada RAB.</td>
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
