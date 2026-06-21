@extends('layouts.app')

@section('title', 'Penyusunan RAB Bangunan')

@section('content')
<div class="ibox">
    <div class="ibox-head bg-light">
        <div class="ibox-title"><i class="fa fa-calculator text-primary"></i> Formulir Penyusunan RAB</div>
        <div class="ibox-tools">
            <span class="badge badge-info p-2">{{ $permintaan->nomor_permintaan }}</span>
        </div>
    </div>
    
    <div class="ibox-body">
        <div class="alert alert-secondary">
            <strong>Proyek:</strong> Pembangunan {{ $permintaan->tipeRumah->nama_tipe }} ({{ number_format($permintaan->luas_bangunan, 2) }} m²) | 
            <strong>Konsumen:</strong> {{ $permintaan->konsumen->name }} | 
            <strong>Lokasi:</strong> {{ $permintaan->lokasi_proyek }}
        </div>

        @if($permintaan->status === \App\Enums\PermintaanStatus::DITOLAK_KONSUMEN && $existingRab && $existingRab->alasan_tolak)
            <div class="alert alert-danger mb-4">
                <h5 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> RAB Sebelumnya Ditolak</h5>
                <p class="mb-0"><strong>Alasan dari Konsumen:</strong> {{ $existingRab->alasan_tolak }}</p>
                <hr>
                <small>Silakan revisi rincian anggaran atau sketsa denah Anda sesuai dengan catatan dari konsumen di atas.</small>
            </div>
        @endif

        <form action="{{ route('tukang.rab.store') }}" method="POST" id="rabForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="permintaan_id" value="{{ $permintaan->id }}">
            
            @if($permintaan->sumber_denah === 'dibuatkan_tukang')
            <div class="alert alert-info mt-3">
                <h5 class="alert-heading"><i class="fa fa-info-circle"></i> Permintaan Denah dari Konsumen</h5>
                <p>Konsumen meminta Anda merancang denah untuk proyek ini. Silakan unggah sketsa/rancangan denah Anda di sini agar bisa dilihat dan disetujui konsumen bersamaan dengan RAB ini.</p>
                
                @if($permintaan->dokumen_path)
                    <div class="card mb-3 border-info">
                        <div class="card-body bg-white p-3">
                            <strong>Denah Saat Ini:</strong><br>
                            @php
                                $ext = pathinfo($permintaan->dokumen_path, PATHINFO_EXTENSION);
                            @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                <img src="{{ asset('storage/' . $permintaan->dokumen_path) }}" class="img-fluid border mt-2 mb-2" style="max-height: 150px;" alt="Sketsa Denah Saat Ini"><br>
                            @endif
                            <a href="{{ asset('storage/' . $permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> Lihat Denah</a>
                        </div>
                    </div>
                @endif

                <div class="form-group mt-2">
                    <label class="font-weight-bold">Upload Sketsa/Rancangan Denah Anda <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" name="dokumen_denah" accept=".jpg,.jpeg,.png,.pdf" {{ empty($permintaan->dokumen_path) ? 'required' : '' }}>
                    @if($permintaan->dokumen_path)
                        <small class="text-muted d-block mt-1">Anda sudah mengunggah denah sebelumnya. <strong>Kosongkan bagian ini</strong> jika Anda tidak ingin mengubah denah, dan cukup revisi bagian anggarannya saja.</small>
                    @endif
                    @error('dokumen_denah')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @endif
            
            <h4 class="text-primary mt-4 mb-3 border-bottom pb-2">Rincian Anggaran Biaya (Pekerjaan & Material)</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="rabTable">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">Tipe</th>
                            <th width="30%">Nama Item</th>
                            <th width="15%">Volume (Qty)</th>
                            <th width="15%">Satuan</th>
                            <th width="25%">Harga Satuan (Rp)</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rabBody">
                        <!-- Dynamic Rows -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold" onclick="addPekerjaanRow()">
                                    <i class="fa fa-plus"></i> Tambah Pekerjaan
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm ml-2" onclick="addMaterialRow()">
                                    <i class="fa fa-plus"></i> Tambah Material Ekstra
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h4 class="text-primary mt-5 mb-3 border-bottom pb-2">Biaya Tambahan, Pajak & Margin</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Biaya Jasa Kepala Tukang (Rp)</label>
                        <select class="form-control mb-2" id="jasaSelect" onchange="setJasaValue()">
                            <option value="0">-- Input Manual --</option>
                            @foreach($jasaTukangs as $jasa)
                                <option value="{{ $jasa->harga }}">{{ $jasa->nama_jasa }}: Rp {{ number_format($jasa->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                        <input type="number" class="form-control" name="biaya_jasa_tukang" id="biaya_jasa_tukang" value="{{ (float)($existingRab->biaya_jasa_tukang ?? 0) }}" min="0" onkeyup="calculateGrandTotal()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Biaya Tambahan Lain-lain (Rp)</label>
                        <input type="number" class="form-control" name="biaya_tambahan" id="biaya_tambahan" value="{{ (float)($existingRab->biaya_tambahan ?? 0) }}" min="0" onkeyup="calculateGrandTotal()">
                        <small class="text-muted">Biaya tak terduga, transportasi, dll.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Margin Keuntungan Tukang (%)</label>
                        <input type="number" class="form-control" name="profit_persen" id="profit_persen" value="{{ (float)($existingRab->profit_persen ?? 0) }}" min="0" max="100" step="0.1" onkeyup="calculateGrandTotal()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-4 mt-2">
                        <label class="ui-checkbox ui-checkbox-primary">
                            <input type="checkbox" id="use_ppn" name="use_ppn" value="1" {{ ($existingRab->ppn_persen ?? 0) > 0 ? 'checked' : '' }} onchange="calculateGrandTotal()">
                            <span class="input-span"></span> Tambahkan PPN 11%
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label class="font-weight-bold">Catatan untuk Konsumen</label>
                <textarea name="catatan_tukang" class="form-control" rows="3" placeholder="Contoh: Perkiraan waktu pengerjaan 3 bulan. Harga material bisa berubah mengikuti harga pasar saat eksekusi.">{{ $existingRab->catatan_tukang ?? '' }}</textarea>
            </div>

            <div class="card bg-light border-primary mt-4">
                <div class="card-body">
                    <div class="row text-right">
                        <div class="col-md-8 offset-md-4">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Subtotal (Upah + Material + Tambahan):</td>
                                    <td width="30%" id="subtotalDisplay" class="font-weight-bold">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>Profit Tukang (<span id="profitLabel">0</span>%):</td>
                                    <td id="profitDisplay" class="text-success">Rp 0</td>
                                </tr>
                                <tr>
                                    <td>PPN (<span id="ppnLabel">0</span>%):</td>
                                    <td id="ppnDisplay" class="text-danger">Rp 0</td>
                                </tr>
                                <tr style="border-top: 2px solid #ccc;">
                                    <td><h5 class="m-0 font-weight-bold text-primary">GRAND TOTAL:</h5></td>
                                    <td><h4 class="m-0 font-weight-bold text-primary" id="grandTotalDisplay">Rp 0</h4></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-right">
                <a href="{{ route('tukang.permintaan.show', $permintaan->id) }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Simpan Draft RAB</button>
            </div>
        </form>
    </div>
</div>

<!-- Templates for JS -->
<template id="materialRowTemplate">
    <tr class="row-material __CLASS__">
        <td class="text-right text-muted align-middle"><i class="fa fa-level-up fa-rotate-90"></i> Material</td>
        <td>
            <select name="__NAME__[material_id]" class="form-control material-select" onchange="updateMaterialPrice(this)" required>
                <option value="">-- Pilih Material --</option>
                @foreach($materials as $item)
                    <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}" data-harga="{{ App\Models\HargaMaterial::where('material_id', $item->id)->orderBy('tanggal_berlaku', 'desc')->first()->harga ?? 0 }}">
                        {{ $item->nama_material }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="__NAME__[qty]" class="form-control item-qty" value="1" min="0.01" step="0.01" onkeyup="calculateGrandTotal()">
        </td>
        <td>
            <input type="text" name="__NAME__[satuan]" class="form-control item-satuan" required readonly>
        </td>
        <td>
            <input type="number" name="__NAME__[harga_satuan]" class="form-control item-harga" value="0" min="0" onkeyup="calculateGrandTotal()" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-warning btn-sm" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
        </td>
    </tr>
</template>

<template id="pekerjaanRowTemplate">
    <tr class="bg-light font-weight-bold row-pekerjaan" data-index="__INDEX__">
        <td class="align-middle"><span class="badge badge-primary">Pekerjaan</span></td>
        <td>
            <select name="pekerjaans[__INDEX__][pekerjaan_id]" class="form-control pekerjaan-select" onchange="updatePekerjaanPrice(this)" required>
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach($pekerjaans as $item)
                    <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}" data-harga="{{ App\Models\HargaPekerjaan::where('pekerjaan_id', $item->id)->orderBy('tanggal_berlaku', 'desc')->first()->harga ?? 0 }}">
                        {{ $item->nama_pekerjaan }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="pekerjaans[__INDEX__][qty]" class="form-control item-qty" value="1" min="0.01" step="0.01" onkeyup="calculateGrandTotal()">
        </td>
        <td>
            <input type="text" name="pekerjaans[__INDEX__][satuan]" class="form-control item-satuan" required readonly>
        </td>
        <td>
            <input type="number" name="pekerjaans[__INDEX__][harga_satuan]" class="form-control item-harga" value="0" min="0" onkeyup="calculateGrandTotal()" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="removePekerjaanRow(this)"><i class="fa fa-trash"></i></button>
        </td>
    </tr>
</template>

@endsection

@push('js')
<script>
    let materialIndex = 0;
    let pekerjaanIndex = 0;

    function addMaterialRow(btn = null) {
        const template = document.getElementById('materialRowTemplate').innerHTML;
        let html = '';
        
        if (btn) {
            let parentRow = $(btn).closest('tr.row-pekerjaan');
            let pekIdx = parentRow.data('index');
            let mName = `pekerjaans[${pekIdx}][materials][${materialIndex++}]`;
            html = template.replace(/__NAME__/g, mName).replace(/__CLASS__/g, `child-of-${pekIdx}`);
            parentRow.after(html);
        } else {
            let mName = `materials[${materialIndex++}]`;
            html = template.replace(/__NAME__/g, mName).replace(/__CLASS__/g, '');
            $('#rabBody').append(html);
        }
    }

    function addMaterialRowWithData(refId, nama, qty, satuan, harga, parentRow = null) {
        const template = document.getElementById('materialRowTemplate').innerHTML;
        let html = '';
        
        if (parentRow) {
            let pekIdx = $(parentRow).data('index');
            let mName = `pekerjaans[${pekIdx}][materials][${materialIndex++}]`;
            html = template.replace(/__NAME__/g, mName).replace(/__CLASS__/g, `child-of-${pekIdx}`);
        } else {
            let mName = `materials[${materialIndex++}]`;
            html = template.replace(/__NAME__/g, mName).replace(/__CLASS__/g, '');
        }

        const row = $(html);
        
        if (refId) {
            row.find('.material-select').val(refId);
            row.find('.item-satuan').attr('readonly', true);
            row.find('.item-harga').attr('readonly', true);
        }
        
        row.find('.item-qty').val(qty);
        row.find('.item-satuan').val(satuan);
        row.find('.item-harga').val(harga);
        
        if (parentRow) {
            // Append as last child
            let children = $(`.child-of-${$(parentRow).data('index')}`);
            if(children.length > 0) {
                children.last().after(row);
            } else {
                parentRow.after(row);
            }
        } else {
            $('#rabBody').append(row);
        }
    }

    function addPekerjaanRow() {
        const template = document.getElementById('pekerjaanRowTemplate').innerHTML;
        const html = template.replace(/__INDEX__/g, pekerjaanIndex++);
        $('#rabBody').append(html);
    }

    function removeRow(btn) {
        $(btn).closest('tr').remove();
        calculateGrandTotal();
    }

    function removePekerjaanRow(btn) {
        let row = $(btn).closest('tr');
        let pekIdx = row.data('index');
        $(`.child-of-${pekIdx}`).remove();
        row.remove();
        calculateGrandTotal();
    }

    function updatePekerjaanPrice(select) {
        const row = $(select).closest('tr');
        const satuanInput = row.find('.item-satuan');
        const hargaInput = row.find('.item-harga');
        
        if(select.value !== "") {
            const satuan = $(select.options[select.selectedIndex]).data('satuan');
            const harga = $(select.options[select.selectedIndex]).data('harga');
            satuanInput.val(satuan);
            hargaInput.val(harga);

            let pekerjaanId = select.value;
            let qtyPekerjaan = parseFloat(row.find('.item-qty').val()) || 1;
            
            $.get('/api/pekerjaan/' + pekerjaanId + '/materials', function(data) {
                if(data.materials && data.materials.length > 0) {
                    Swal.fire({
                        title: 'Komposisi Material',
                        text: "Pekerjaan ini memiliki " + data.materials.length + " material bawaan. Tambahkan ke daftar otomatis?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Tambahkan',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            data.materials.forEach(function(mat) {
                                let totalQty = mat.qty_dasar * qtyPekerjaan;
                                addMaterialRowWithData(mat.id, mat.nama_material, totalQty, mat.satuan, mat.harga_satuan, row);
                            });
                            calculateGrandTotal();
                            Swal.fire('Berhasil!', 'Material telah ditambahkan di bawah pekerjaan.', 'success');
                        }
                    });
                }
            });
        }
        calculateGrandTotal();
    }

    function setJasaValue() {
        const val = $('#jasaSelect').val();
        if(val > 0) {
            $('#biaya_jasa_tukang').val(val);
        }
        calculateGrandTotal();
    }

    function updateMaterialPrice(select) {
        const row = $(select).closest('tr');
        const satuanInput = row.find('.item-satuan');
        const hargaInput = row.find('.item-harga');
        
        if(select.value !== "") {
            const satuan = $(select.options[select.selectedIndex]).data('satuan');
            const harga = $(select.options[select.selectedIndex]).data('harga');
            satuanInput.val(satuan);
            hargaInput.val(harga);
        }
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subtotal = 0;
        
        // Sum all rabBody rows
        $('#rabBody tr').each(function() {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const harga = parseFloat($(this).find('.item-harga').val()) || 0;
            subtotal += (qty * harga);
        });

        // Jasa & Tambahan
        const jasa = parseFloat($('#biaya_jasa_tukang').val()) || 0;
        const tambahan = parseFloat($('#biaya_tambahan').val()) || 0;
        
        subtotal += jasa + tambahan;

        let profitPersen = parseFloat($('#profit_persen').val()) || 0;
        let profitNominal = subtotal * (profitPersen / 100);
        
        let subAfterProfit = subtotal + profitNominal;
        
        let ppnPersen = $('#use_ppn').is(':checked') ? 11 : 0;
        let ppnNominal = subAfterProfit * (ppnPersen / 100);
        
        let grandTotal = subAfterProfit + ppnNominal;

        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        });

        $('#subtotalDisplay').text(formatter.format(subtotal));
        
        $('#profitLabel').text(profitPersen);
        $('#profitDisplay').text(formatter.format(profitNominal));
        
        $('#ppnLabel').text(ppnPersen);
        $('#ppnDisplay').text(formatter.format(ppnNominal));

        $('#grandTotalDisplay').text(formatter.format(grandTotal));
    }

    // Initialize with one row each or existing data
    $(document).ready(function() {
        @if($existingRab && $existingRab->details->count() > 0)
            // Restore legacy RAB or nested RAB
            @php
                $pekerjaans = $existingRab->details->where('jenis_item', 'pekerjaan');
                $orphanMaterials = $existingRab->details->where('jenis_item', 'material')->where('parent_id', null);
            @endphp
            
            @foreach($pekerjaans as $item)
                let pekRow_{{ $item->id }} = addPekerjaanRowWithData(@json($item->referensi_id), @json($item->nama_item), {{ (float)$item->qty }}, @json($item->satuan), {{ (float)$item->harga_satuan }});
                @foreach($item->children as $child)
                    addMaterialRowWithData(@json($child->referensi_id), @json($child->nama_item), {{ (float)$child->qty }}, @json($child->satuan), {{ (float)$child->harga_satuan }}, pekRow_{{ $item->id }});
                @endforeach
            @endforeach
            
            @foreach($orphanMaterials as $item)
                addMaterialRowWithData(@json($item->referensi_id), @json($item->nama_item), {{ (float)$item->qty }}, @json($item->satuan), {{ (float)$item->harga_satuan }});
            @endforeach
            
            calculateGrandTotal();
        @else
            addPekerjaanRow();
        @endif
        
        $('#rabForm').on('submit', function(e) {
            if ($('#rabBody tr').length === 0) {
                e.preventDefault();
                Swal.fire('Peringatan', 'RAB harus memiliki minimal 1 item material atau pekerjaan.', 'warning');
            }
        });
    });

    function addPekerjaanRowWithData(refId, nama, qty, satuan, harga) {
        const template = document.getElementById('pekerjaanRowTemplate').innerHTML;
        const html = template.replace(/__INDEX__/g, pekerjaanIndex++);
        const row = $(html);
        
        if (refId) {
            row.find('.pekerjaan-select').val(refId);
        }
        
        row.find('.item-qty').val(qty);
        row.find('.item-satuan').val(satuan);
        row.find('.item-harga').val(harga);
        
        $('#rabBody').append(row);
        return row;
    }
</script>
@endpush
