@extends('layouts.app')

@section('title', 'Edit Profil Kepala Tukang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Form Edit Profil</div>
            </div>
            <div class="ibox-body">
                <form action="{{ route('tukang.profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-info mb-3"><i class="fa fa-user"></i> Informasi Akun & Kontak</h5>
                            
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>No. HP</label>
                                <input class="form-control @error('no_hp') is-invalid @enderror" type="text" name="no_hp" value="{{ old('no_hp', $user->profile->no_hp ?? '') }}">
                                @error('no_hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="3">{{ old('alamat', $user->profile->alamat ?? '') }}</textarea>
                                @error('alamat') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-info mb-3"><i class="fa fa-briefcase"></i> Data Profesional</h5>
                            
                            <div class="form-group">
                                <label>Keahlian Utama</label>
                                <input class="form-control @error('keahlian') is-invalid @enderror" type="text" name="keahlian" value="{{ old('keahlian', $user->profile->keahlian ?? '') }}" placeholder="Contoh: Spesialis Atap Baja Ringan">
                                @error('keahlian') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label>Pengalaman Kerja</label>
                                <input class="form-control @error('pengalaman') is-invalid @enderror" type="text" name="pengalaman" value="{{ old('pengalaman', $user->profile->pengalaman ?? '') }}" placeholder="Contoh: 10 Tahun">
                                @error('pengalaman') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Deskripsi / Portofolio Singkat</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3" placeholder="Ceritakan pengalaman dan proyek yang pernah Anda kerjakan...">{{ old('deskripsi', $user->profile->deskripsi ?? '') }}</textarea>
                                @error('deskripsi') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="form-group">
                                <label>Foto Profil</label>
                                <input class="form-control @error('foto') is-invalid @enderror" type="file" name="foto" accept="image/*">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto. (Maks: 2MB)</small>
                                @error('foto') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="form-group text-right mb-0">
                        <a href="{{ route('tukang.profil') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
