@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Form Edit Profil</div>
            </div>
            <div class="ibox-body">
                <form action="{{ route('konsumen.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No. HP</label>
                        <div class="col-sm-9">
                            <input class="form-control @error('no_hp') is-invalid @enderror" type="text" name="no_hp" value="{{ old('no_hp', $user->profile->no_hp ?? '') }}">
                            @error('no_hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                        <div class="col-sm-9">
                            <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', $user->profile->alamat ?? '') }}</textarea>
                            @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Foto Profil</label>
                        <div class="col-sm-9">
                            <input class="form-control @error('foto') is-invalid @enderror" type="file" name="foto" accept="image/*">
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. (Maks: 2MB)</small>
                            @error('foto') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan Perubahan</button>
                            <a href="{{ route('konsumen.profil') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
