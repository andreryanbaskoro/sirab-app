@extends('layouts.app')

@section('title', 'Riwayat Permintaan RAB')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Riwayat Permintaan RAB</div>
        <div class="ibox-tools">
            <a href="{{ route('konsumen.permintaan.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Buat Permintaan Baru
            </a>
        </div>
    </div>
    <div class="ibox-body">
        
        <form method="GET" action="{{ route('konsumen.permintaan.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>Filter Status</label>
                    <select name="status" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('konsumen.permintaan.index') }}" class="btn btn-default">Reset Filter</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="datatable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Permintaan</th>
                        <th>Kepala Tukang</th>
                        <th>Tipe Rumah</th>
                        <th>Tgl Permohonan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $item->nomor_permintaan }}</td>
                        <td>{{ $item->tukang->name }}</td>
                        <td>{{ $item->tipeRumah->nama_tipe }}</td>
                        <td>{{ $item->tanggal_permohonan->format('d/m/Y') }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                        <td>
                            <a href="{{ route('konsumen.permintaan.show', $item->id) }}" class="btn btn-info btn-xs" title="Lihat Detail">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            paging: false,
            info: false,
            searching: true
        });
    });
</script>
@endpush
