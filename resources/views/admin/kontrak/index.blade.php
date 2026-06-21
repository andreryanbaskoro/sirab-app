@extends('layouts.app')

@section('title', 'Daftar Kontrak (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Pemantauan Kontrak Kerja</div>
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. Kontrak</th>
                        <th>Konsumen</th>
                        <th>Tukang</th>
                        <th>Masa Kerja</th>
                        <th>Nilai Kontrak</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $item->nomor_kontrak }}</td>
                        <td>{{ $item->konsumen->name }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td>Rp {{ number_format($item->nilai_kontrak, 0, ',', '.') }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                        <td>
                            <a href="{{ route('admin.kontrak.show', $item->id) }}" class="btn btn-info btn-xs">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>
    </div>
</div>
@endsection
