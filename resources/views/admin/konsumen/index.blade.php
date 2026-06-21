@extends('layouts.app')
@section('title', 'Data Konsumen')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Data Konsumen</div>
    </div>
    <div class="ibox-body">
        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->name }}</td>
                    <td>{{ $k->email }}</td>
                    <td>{{ $k->profile->no_hp ?? '-' }}</td>
                    <td>{{ $k->profile->alamat ?? '-' }}</td>
                    <td>
                        @if($k->status_aktif)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
    $(function() {
        $('#example-table').DataTable({
            pageLength: 10,
        });
    });
</script>
@endpush
