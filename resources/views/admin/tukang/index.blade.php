@extends('layouts.app')

@section('title', 'Data Kepala Tukang')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Kepala Tukang</div>
        <div class="ibox-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal"><i class="fa fa-plus"></i> Tambah Tukang</button>
        </div>
    </div>
    <div class="ibox-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tukang</th>
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th>Pengalaman</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->profile->no_hp ?? '-' }}</td>
                        <td>{{ $item->profile->pengalaman ?? '-' }}</td>
                        <td>
                            @if($item->status_aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal{{ $item->id }}"><i class="fa fa-pencil"></i></button>
                            <form action="{{ route('admin.tukang.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $item->id }}">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.tukang.update', $item->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Edit Tukang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                    <div class="modal-body">
                                        <div class="form-group"><label>Nama</label><input type="text" name="name" class="form-control" value="{{ $item->name }}" required></div>
                                        <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ $item->email }}" required></div>
                                        <div class="form-group"><label>No HP</label><input type="text" name="no_hp" class="form-control" value="{{ $item->profile->no_hp ?? '' }}"></div>
                                        <div class="form-group"><label>Pengalaman</label><input type="text" name="pengalaman" class="form-control" value="{{ $item->profile->pengalaman ?? '' }}"></div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status_aktif" class="form-control">
                                                <option value="1" {{ $item->status_aktif ? 'selected' : '' }}>Aktif</option>
                                                <option value="0" {{ !$item->status_aktif ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                        <div class="form-group"><label>Password (Kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
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

<!-- Create Modal -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.tukang.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Tambah Tukang Baru</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label>Nama <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label>Password <span class="text-danger">*</span></label><input type="password" name="password" class="form-control" required></div>
                    <div class="form-group"><label>No HP</label><input type="text" name="no_hp" class="form-control"></div>
                    <div class="form-group"><label>Pengalaman</label><input type="text" name="pengalaman" class="form-control" placeholder="Contoh: 5 Tahun di konstruksi"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
            </div>
        </form>
    </div>
</div>
@endsection
