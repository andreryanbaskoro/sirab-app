@extends('layouts.app')

@section('title', 'Data Tipe Rumah')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Tipe Rumah</div>
        <div class="ibox-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal"><i class="fa fa-plus"></i> Tambah Tipe Rumah</button>
        </div>
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe Rumah</th>
                        <th>Deskripsi</th>
                        <th>Luas (m²)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->nama_tipe }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td>{{ $item->luas }}</td>
                        <td>
                            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                            <form action="{{ route('admin.tipe-rumah.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $item->id }}">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.tipe-rumah.update', $item->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Tipe Rumah</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                    <div class="modal-body">
                                        <div class="form-group"><label>Nama Tipe</label><input type="text" name="nama_tipe" class="form-control" value="{{ $item->nama_tipe }}" required></div>
                                        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control">{{ $item->deskripsi }}</textarea></div>
                                        <div class="form-group"><label>Luas (m²)</label><input type="number" step="0.01" name="luas" class="form-control" value="{{ $item->luas }}" required></div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="6" class="text-center">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">{{ $data->links() }}</div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.tipe-rumah.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Tipe Rumah</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label>Nama Tipe <span class="text-danger">*</span></label><input type="text" name="nama_tipe" class="form-control" placeholder="Contoh: Tipe 36" required></div>
                    <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"></textarea></div>
                    <div class="form-group"><label>Luas (m²)</label><input type="number" step="0.01" name="luas" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
