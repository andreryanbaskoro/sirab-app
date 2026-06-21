@extends('layouts.app')

@section('title', 'Data Material')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Material Bangunan</div>
        <div class="ibox-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal"><i class="fa fa-plus"></i> Tambah Material</button>
        </div>
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Material</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th>Harga Saat Ini</th>
                        <th>Tanggal Berlaku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->nama_material }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        @php $hargaTerbaru = $item->hargaMaterials->sortByDesc('tanggal_berlaku')->first(); @endphp
                        <td>{{ $hargaTerbaru ? 'Rp ' . number_format($hargaTerbaru->harga, 0, ',', '.') : '-' }}</td>
                        <td>{{ $hargaTerbaru ? \Carbon\Carbon::parse($hargaTerbaru->tanggal_berlaku)->format('d M Y') : '-' }}</td>
                        <td>
                            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                            <form action="{{ route('admin.material.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $item->id }}">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.material.update', $item->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Material</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                    <div class="modal-body">
                                        <div class="form-group"><label>Nama Material</label><input type="text" name="nama_material" class="form-control" value="{{ $item->nama_material }}" required></div>
                                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan" class="form-control" value="{{ $item->satuan }}" required></div>
                                        <div class="form-group"><label>Keterangan</label><textarea name="deskripsi" class="form-control">{{ $item->deskripsi }}</textarea></div>
                                        @php $hargaTerbaru = $item->hargaMaterials->sortByDesc('tanggal_berlaku')->first(); @endphp
                                        <div class="form-group"><label>Harga (Rp) <span class="text-danger">*</span></label><input type="number" name="harga" class="form-control" value="{{ $hargaTerbaru ? (int)$hargaTerbaru->harga : '' }}" required></div>
                                        <div class="form-group"><label>Tanggal Berlaku <span class="text-danger">*</span></label><input type="date" name="tanggal_berlaku" class="form-control" value="{{ $hargaTerbaru ? $hargaTerbaru->tanggal_berlaku : date('Y-m-d') }}" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>
    </div>
</div>

<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.material.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Material</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label>Nama Material <span class="text-danger">*</span></label><input type="text" name="nama_material" class="form-control" placeholder="Semen Holcim 50kg" required></div>
                    <div class="form-group"><label>Satuan <span class="text-danger">*</span></label><input type="text" name="satuan" class="form-control" placeholder="Sak" required></div>
                    <div class="form-group"><label>Keterangan</label><textarea name="deskripsi" class="form-control"></textarea></div>
                    <div class="form-group"><label>Harga (Rp) <span class="text-danger">*</span></label><input type="number" name="harga" class="form-control" required></div>
                    <div class="form-group"><label>Tanggal Berlaku <span class="text-danger">*</span></label><input type="date" name="tanggal_berlaku" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
