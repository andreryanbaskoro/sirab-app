@extends('layouts.app')

@section('title', 'Detail Permintaan (Admin)')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Detail Permintaan Proyek: {{ $permintaan->nomor_permintaan }}</div>
                <div class="ibox-tools">
                    <a href="{{ route('admin.permintaan.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="ibox-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light" width="30%">Status</th>
                                <td><x-status-badge :status="$permintaan->status" /></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Tanggal Permohonan</th>
                                <td>{{ $permintaan->tanggal_permohonan->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Konsumen</th>
                                <td>{{ $permintaan->konsumen->name }} <br><small class="text-muted">{{ $permintaan->konsumen->profile->no_hp ?? '' }}</small></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Kepala Tukang</th>
                                <td>{{ $permintaan->tukang->name }} <br><small class="text-muted">{{ $permintaan->tukang->profile->no_hp ?? '' }}</small></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-light" width="30%">Tipe Rumah</th>
                                <td>{{ $permintaan->tipeRumah->nama_tipe }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Jenis Jasa</th>
                                <td>{{ ucfirst($permintaan->jenis_jasa) }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Luas Bangunan</th>
                                <td>{{ $permintaan->luas_bangunan }} m²</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Lokasi</th>
                                <td>{{ $permintaan->lokasi_proyek }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Lampiran</th>
                                <td>
                                    @if($permintaan->dokumen_path)
                                        <a href="{{ asset('storage/' . $permintaan->dokumen_path) }}" target="_blank" class="text-info">Lihat Dokumen</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($permintaan->rab)
                <h5 class="mt-4 mb-3 border-bottom pb-2">Keterkaitan Dokumen</h5>
                <a href="{{ route('admin.rab.show', $permintaan->rab->id) }}" class="btn btn-outline-info mr-2"><i class="fa fa-file-text"></i> RAB: {{ $permintaan->rab->nomor_rab }}</a>
                @endif
                
                @if($permintaan->kontrak)
                <a href="{{ route('admin.kontrak.show', $permintaan->kontrak->id) }}" class="btn btn-outline-success"><i class="fa fa-handshake-o"></i> SPK: {{ $permintaan->kontrak->nomor_kontrak }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
