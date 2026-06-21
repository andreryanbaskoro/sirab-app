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

        <form action="{{ route('tukang.rab.store') }}" method="POST" id="rabForm">
            @csrf
            <input type="hidden" name="permintaan_id" value="{{ $permintaan->id }}">
            
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

            <h4 class="text-primary mt-5 mb-3 border-bottom pb-2">Biaya Jasa & Tambahan</h4>
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
                        <input type="number" class="form-control" name="biaya_jasa_tukang" id="biaya_jasa_tukang" value="{{ $existingRab->biaya_jasa_tukang ?? 0 }}" min="0" onkeyup="calculateGrandTotal()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Biaya Tambahan Lain-lain (Rp)</label>
                        <input type="number" class="form-control" name="biaya_tambahan" id="biaya_tambahan" value="{{ $existingRab->biaya_tambahan ?? 0 }}" min="0" onkeyup="calculateGrandTotal()">
                        <small class="text-muted">Biaya tak terduga, transportasi, dll.</small>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label class="font-weight-bold">Catatan untuk Konsumen</label>
                <textarea name="catatan_tukang" class="form-control" rows="3" placeholder="Contoh: Perkiraan waktu pengerjaan 3 bulan. Harga material bisa berubah mengikuti harga pasar saat eksekusi.">{{ $existingRab->catatan_tukang ?? '' }}</textarea>
            </div>

            <div class="card bg-light border-primary mt-4">
                <div class="card-body text-right">
                    <h5 class="text-muted mb-1">Estimasi Grand Total:</h5>
                    <h2 class="text-primary font-strong m-0" id="grandTotalDisplay">Rp 0</h2>
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
    <tr class="row-material">
        <td class="text-right text-muted align-middle"><i class="fa fa-level-up fa-rotate-90"></i> Material</td>
        <td>
            <select name="materials[__INDEX__][material_id]" class="form-control material-select" onchange="updateMaterialPrice(this)" required>
                <option value="">-- Pilih Material --</option>
                @foreach($materials as $item)
                    <option value="{{ $item->id }}" data-satuan="{{ $item->satuan }}" data-harga="{{ App\Models\HargaMaterial::where('material_id', $item->id)->orderBy('tanggal_berlaku', 'desc')->first()->harga ?? 0 }}">
                        {{ $item->nama_material }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="materials[__INDEX__][qty]" class="form-control item-qty" value="1" min="0.01" step="0.01" onkeyup="calculateGrandTotal()">
        </td>
        <td>
            <input type="text" name="materials[__INDEX__][satuan]" class="form-control item-satuan" required readonly>
        </td>
        <td>
            <input type="number" name="materials[__INDEX__][harga_satuan]" class="form-control item-harga" value="0" min="0" onkeyup="calculateGrandTotal()" readonly>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-warning btn-sm" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
        </td>
    </tr>
</template>

<template id="pekerjaanRowTemplate">
    <tr class="bg-light font-weight-bold row-pekerjaan">
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
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
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
        const html = template.replace(/__INDEX__/g, materialIndex++);
        
        if (btn) {
            $(btn).closest('tr').after(html);
        } else {
            $('#rabBody').append(html);
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
        // Hapus semua baris material di bawahnya sampai ketemu pekerjaan lain
        let nextRows = row.nextUntil('.row-pekerjaan');
        nextRows.remove();
        row.remove();
        calculateGrandTotal();
    }

    function updateMaterialPrice(select) {
        // Logic handled manually in input fields
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
                            let reversedMaterials = [...data.materials].reverse();
                            reversedMaterials.forEach(function(mat) {
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
        let total = 0;
        
        // Sum all rabBody rows
        $('#rabBody tr').each(function() {
            const qty = parseFloat($(this).find('.item-qty').val()) || 0;
            const harga = parseFloat($(this).find('.item-harga').val()) || 0;
            total += (qty * harga);
        });

        // Jasa & Tambahan
        const jasa = parseFloat($('#biaya_jasa_tukang').val()) || 0;
        const tambahan = parseFloat($('#biaya_tambahan').val()) || 0;
        
        total += jasa + tambahan;

        // Format to IDR
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });

        $('#grandTotalDisplay').text(formatter.format(total));
    }

    function updateChildMaterialsQty(pekerjaanInput) {
        let pekerjaanRow = $(pekerjaanInput).closest('tr.row-pekerjaan');
        let newQty = parseFloat($(pekerjaanInput).val()) || 1;
        
        // Find all subsequent row-material until the next row-pekerjaan
        let nextRows = pekerjaanRow.nextUntil('.row-pekerjaan', '.row-material');
        nextRows.each(function() {
            let dasarQty = parseFloat($(this).data('dasar-qty')) || parseFloat($(this).find('.item-qty').val()); // this is tricky, we didn't store dasar-qty
            // It's better not to auto-update children quantities if they manually edited them. 
            // Or we just update them if they haven't been manually edited. Let's just leave it out for simplicity.
        });
    }

    function addMaterialRowWithData(refId, nama, qty, satuan, harga, parentRow = null) {
        const template = document.getElementById('materialRowTemplate').innerHTML;
        const html = template.replace(/__INDEX__/g, materialIndex++);
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
            parentRow.after(row);
        } else {
            $('#rabBody').append(row);
        }
    }

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

    // Initialize with one row each or existing data
    $(document).ready(function() {
        @if($existingRab && $existingRab->details->count() > 0)
            // It's a flat list from DB. Ideally we'd need logic to group them.
            // For now, just append all pekerjaans then all materials
            @php
                $pekerjaans = $existingRab->details->where('jenis_item', 'pekerjaan');
                $materials = $existingRab->details->where('jenis_item', 'material');
            @endphp
            
            @foreach($pekerjaans as $item)
                addPekerjaanRowWithData(@json($item->referensi_id), @json($item->nama_item), {{ (float)$item->qty }}, @json($item->satuan), {{ (float)$item->harga_satuan }});
            @endforeach
            
            @foreach($materials as $item)
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
</script>
@endpush
