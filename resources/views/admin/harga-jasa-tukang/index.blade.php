@extends('layouts.app')

@section('title', 'Tarif Jasa Kepala Tukang')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Tarif Harian Kepala Tukang</div>
        <!-- Fitur Tambah dinonaktifkan -->
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tukang</th>
                        <th>Nama Jasa</th>
                        <th>Harga (Rp)</th>
                        <th>Deskripsi</th>
                        <!-- Aksi dihapus -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>{{ $item->nama_jasa }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        <!-- Aksi dihapus -->
                    @empty
                    <tr><td colspan="5" class="text-center">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>
    </div>
</div>

<!-- Modals removed -->
@endsection
