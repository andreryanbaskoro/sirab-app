@extends('layouts.app')

@section('title', 'Profil Admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Informasi Profil</div>
                <div class="ibox-tools">
                    <a href="{{ route('admin.profil.edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit Profil</a>
                </div>
            </div>
            <div class="ibox-body text-center">
                <div class="m-b-20">
                    @if($user->profile && $user->profile->foto)
                        <img class="img-circle" src="{{ asset('storage/' . $user->profile->foto) }}" alt="Foto Profil" width="120" height="120" style="object-fit: cover;">
                    @else
                        <img class="img-circle" src="{{ asset('themes/assets/img/admin-avatar.png') }}" alt="Default Avatar" width="120">
                    @endif
                </div>
                <h4 class="font-strong">{{ $user->name }}</h4>
                <div class="text-muted mb-4">{{ $user->email }}</div>

                <table class="table table-striped table-bordered text-left">
                    <tbody>
                        <tr>
                            <th width="30%">Nama Lengkap</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $user->profile->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Lengkap</th>
                            <td>{{ $user->profile->alamat ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
