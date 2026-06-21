@extends('layouts.app')

@section('title', 'Daftar Kepala Tukang')

@section('content')
<div class="row">
    @forelse($tukangs as $tukang)
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <img src="{{ $tukang->foto_profil }}" class="rounded-circle mb-3 border border-secondary" width="100" height="100" style="object-fit: cover;" alt="Foto Profil">
                <h4 class="font-strong mb-1">{{ $tukang->name }}</h4>
                <p class="text-muted"><i class="fa fa-map-marker"></i> {{ $tukang->profile->alamat ?? 'Alamat belum diatur' }}</p>
                
                <div class="text-left mt-3">
                    <p class="mb-1"><small><strong>No HP:</strong> {{ $tukang->profile->no_hp ?? '-' }}</small></p>
                    <p class="mb-1"><small><strong>Pengalaman:</strong> {{ $tukang->profile->pengalaman ?? '-' }}</small></p>
                </div>
                
            </div>
            <div class="card-footer bg-white border-0 text-center">
                <a href="{{ route('konsumen.permintaan.create', ['tukang_id' => $tukang->id]) }}" class="btn btn-primary btn-block rounded-pill">
                    <i class="fa fa-envelope-o"></i> Buat Permintaan
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h4>Belum ada data Kepala Tukang.</h4>
            <p>Silakan hubungi administrator.</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
