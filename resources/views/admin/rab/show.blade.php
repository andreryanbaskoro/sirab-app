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
            <div class="col-md-6">
                <strong>Konsumen:</strong> {{ $rab->permintaan->konsumen->name }}<br>
                <strong>Tukang:</strong> {{ $rab->tukang->name }}<br>
            </div>
            <div class="col-md-6 text-right">
                <h3 class="text-success">Total: Rp {{ number_format($rab->total_final, 0, ',', '.') }}</h3>
            </div>
        </div>
        
        <hr>
        <h5>Rincian Item</h5>
        <x-rab-table :rab="$rab" />
    </div>
</div>
@endsection
