@extends('layouts.app')

@section('title', 'Detail Kontrak (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Surat Perintah Kerja (SPK): {{ $kontrak->nomor_kontrak }}</div>
        <div class="ibox-tools">
            <a href="{{ route('admin.kontrak.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="ibox-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" width="40%">Status SPK</th>
                        <td><x-status-badge :status="$kontrak->status" /></td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tipe Rumah</th>
                        <td>{{ $kontrak->permintaan->tipeRumah->nama_tipe }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Sumber Denah</th>
                        <td>
                            @if($kontrak->permintaan->sumber_denah === 'upload_sendiri')
                                Dari Konsumen
                            @elseif($kontrak->permintaan->sumber_denah === 'dibuatkan_tukang')
                                Dibuatkan Tukang
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @if($kontrak->permintaan->dokumen_path)
                    <tr>
                        <th class="bg-light">File Denah</th>
                        <td><a href="{{ asset('storage/' . $kontrak->permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa fa-download"></i> Lihat Denah</a></td>
                    </tr>
                    @endif
                    <tr>
                        <th class="bg-light">PIHAK PERTAMA (Pemilik)</th>
                        <td>{{ $kontrak->konsumen->name }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">PIHAK KEDUA (Tukang)</th>
                        <td>{{ $kontrak->tukang->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light" width="40%">Nilai Kontrak</th>
                        <td class="text-success font-weight-bold">Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tanggal Mulai</th>
                        <td>{{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Tanggal Selesai (Target)</th>
                        <td>{{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>Dokumen Acuan:</h5>
            <a href="{{ route('admin.permintaan.show', $kontrak->permintaan_id) }}" class="btn btn-outline-primary mb-3"><i class="fa fa-file"></i> Permintaan: {{ $kontrak->permintaan->nomor_permintaan }}</a>
            
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Rencana Anggaran Biaya (RAB: {{ $kontrak->rab->nomor_rab }})</h5>
                </div>
                <div class="card-body bg-white">
                    <x-rab-table :rab="$kontrak->rab" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
