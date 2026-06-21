@extends('layouts.app')

@section('title', 'Riwayat Permintaan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Riwayat Proyek Selesai & Ditolak</div>
            </div>
            <div class="ibox-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Permintaan</th>
                                <th>Kepala Tukang</th>
                                <th>Tipe Rumah</th>
                                <th>Lokasi Proyek</th>
                                <th>Status Akhir</th>
                                <th>Tgl Permohonan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $key => $item)
                            <tr>
                                <td>{{ $data->firstItem() + $key }}</td>
                                <td>{{ $item->nomor_permintaan }}</td>
                                <td>{{ $item->tukang->name }}</td>
                                <td>{{ $item->tipeRumah->nama_tipe }}</td>
                                <td>{{ $item->lokasi_proyek }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('konsumen.permintaan.show', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada riwayat permintaan.</td>
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
