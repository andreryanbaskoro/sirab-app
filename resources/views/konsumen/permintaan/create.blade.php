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
                            <div class="check-list">
                                <label class="ui-radio ui-radio-primary ui-radio-inline">
                                    <input type="radio" name="jenis_jasa" value="harian" required>
                                    <span class="input-span"></span>Harian
                                </label>
                                <label class="ui-radio ui-radio-primary ui-radio-inline">
                                    <input type="radio" name="jenis_jasa" value="borongan" required>
                                    <span class="input-span"></span>Borongan
                                </label>
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
                        <label class="col-sm-3 col-form-label">Sumber Denah <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="check-list">
                                <label class="ui-radio ui-radio-primary ui-radio-inline">
                                    <input type="radio" name="sumber_denah" value="upload_sendiri" id="radioUploadSendiri" required {{ old('sumber_denah') == 'upload_sendiri' ? 'checked' : '' }}>
                                    <span class="input-span"></span>Upload Denah Sendiri
                                </label>
                                <label class="ui-radio ui-radio-primary ui-radio-inline">
                                    <input type="radio" name="sumber_denah" value="dibuatkan_tukang" id="radioDibuatkanTukang" required {{ old('sumber_denah') == 'dibuatkan_tukang' ? 'checked' : '' }}>
                                    <span class="input-span"></span>Minta Tukang Buatkan Sketsa/Denah
                                </label>
                            </div>
                            <small class="form-text text-muted" id="infoDibuatkanTukang" style="display:none;"><i class="fa fa-info-circle text-info"></i> Kepala Tukang akan merancang denah kasar berdasarkan catatan dan luas bangunan Anda.</small>
                        </div>
                    </div>

                    <div class="form-group row" id="formUploadDenah">
                        <label class="col-sm-3 col-form-label">Dokumen Referensi / Denah <span class="text-danger" id="reqUpload">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="file" name="dokumen" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Unggah gambar denah, foto lokasi, atau referensi desain (Max: 5MB).</small>
                            @error('dokumen')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
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

        // Toggle Upload Denah based on Sumber Denah
        function toggleDenahUpload() {
            var val = $('input[name="sumber_denah"]:checked').val();
            if (val === 'upload_sendiri') {
                $('#formUploadDenah').show();
                $('#infoDibuatkanTukang').hide();
            } else if (val === 'dibuatkan_tukang') {
                $('#formUploadDenah').hide();
                $('#infoDibuatkanTukang').show();
            } else {
                $('#formUploadDenah').hide();
                $('#infoDibuatkanTukang').hide();
            }
        }

        $('input[name="sumber_denah"]').change(function() {
            toggleDenahUpload();
        });
        
        // initial run
        toggleDenahUpload();
    });
</script>
@endpush
