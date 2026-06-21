@extends('layouts.app')
@section('title', 'Sedang Dibangun')

@section('content')
<div class="row">
    <div class="col-12 text-center mt-5">
        <h1 class="display-4 text-muted"><i class="fa fa-wrench"></i></h1>
        <h3>Halaman Sedang Dalam Pengembangan</h3>
        <p class="text-muted">Fitur ini sedang dibangun dan akan segera tersedia.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-3"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
@endsection
