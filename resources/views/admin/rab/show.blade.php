@extends('layouts.app')

@section('title', 'Detail RAB (Admin)')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Detail RAB: {{ $rab->nomor_rab }}</div>
        <div class="ibox-tools">
            <a href="{{ route('admin.rab.index') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="ibox-body">
        <h4 class="mb-3">Status: <x-status-badge :status="$rab->status" /></h4>
        <div class="row">
            <div class="col-md-4">
                <strong>Konsumen:</strong> {{ $rab->permintaan->konsumen->name }}<br>
                <strong>Tukang:</strong> {{ $rab->tukang->name }}<br>
            </div>
            <div class="col-md-4 text-center">
                <strong>Sketsa Denah:</strong><br>
                @if($rab->permintaan->dokumen_path)
                    @php
                        $ext = pathinfo($rab->permintaan->dokumen_path, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $rab->permintaan->dokumen_path) }}" class="img-fluid border mt-1" style="max-height: 100px;" alt="Sketsa Denah"><br>
                    @endif
                    <a href="{{ asset('storage/' . $rab->permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm mt-1"><i class="fa fa-eye"></i> Lihat Denah</a>
                @else
                    <span class="text-muted">Tidak ada dokumen</span>
                @endif
            </div>
            <div class="col-md-4 text-right">
                <h3 class="text-success">Total: Rp {{ number_format($rab->total_final, 0, ',', '.') }}</h3>
            </div>
        </div>
        
        <hr>
        <h5>Rincian Item</h5>
        <x-rab-table :rab="$rab" />
    </div>
</div>
@endsection
