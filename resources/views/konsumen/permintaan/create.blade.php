@extends('layouts.app')

@section('title', 'Buat Permintaan RAB Baru')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Formulir Permintaan Pembuatan RAB</div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                <form action="{{ route('konsumen.permintaan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih Kepala Tukang <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="tukang_id" class="form-control select2" required>
                                <option value="">-- Pilih Tukang --</option>
                                @foreach($tukangs as $tukang)
                                    <option value="{{ $tukang->id }}" {{ request('tukang_id') == $tukang->id ? 'selected' : '' }}>
                                        {{ $tukang->name }} 
                                        @if($tukang->profile && $tukang->profile->alamat)
                                            - ({{ \Str::limit($tukang->profile->alamat, 30) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kepala tukang yang akan menyusun RAB Anda.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tipe Rumah <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select name="tipe_rumah_id" class="form-control select2" required>
                                <option value="">-- Pilih Tipe Rumah --</option>
                                @foreach($tipeRumahs as $tipe)
                                    <option value="{{ $tipe->id }}">{{ $tipe->nama_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jenis Jasa <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_jasa" id="harian" value="harian" required>
                                <label class="form-check-label" for="harian">Harian</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_jasa" id="borongan" value="borongan" required>
                                <label class="form-check-label" for="borongan">Borongan</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Lokasi Proyek <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="lokasi_proyek" placeholder="Contoh: Jl. Sudirman No. 12, Jakarta" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Luas Bangunan (m²) <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="number" step="0.01" name="luas_bangunan" placeholder="Contoh: 45.5" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Dokumen Referensi</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="dokumen" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Opsional. Unggah gambar denah, foto lokasi, atau referensi desain (Max: 5MB).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Catatan Khusus</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="catatan" rows="4" placeholder="Contoh: Tolong gunakan material kualitas medium. Ada permintaan penambahan kamar mandi dalam."></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button class="btn btn-primary" type="submit">Submit Permintaan</button>
                            <a href="{{ route('konsumen.permintaan.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'classic'
        });
    });
</script>
@endpush
