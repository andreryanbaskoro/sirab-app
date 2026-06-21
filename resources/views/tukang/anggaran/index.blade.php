@extends('layouts.app')

@section('title', 'Data Referensi Anggaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Data Referensi Harga &amp; Jasa</div>
            </div>
            <div class="ibox-body">

                {{-- ── NAV TABS ── --}}
                <ul class="nav nav-tabs tabs-line mb-0" id="anggaranTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $tab === 'pekerjaan' ? 'active' : '' }}"
                           id="tab-pekerjaan-link"
                           data-toggle="tab" href="#tab-pekerjaan" role="tab"
                           aria-selected="{{ $tab === 'pekerjaan' ? 'true' : 'false' }}">
                            <i class="fa fa-wrench"></i> Harga Pekerjaan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab === 'material' ? 'active' : '' }}"
                           id="tab-material-link"
                           data-toggle="tab" href="#tab-material" role="tab"
                           aria-selected="{{ $tab === 'material' ? 'true' : 'false' }}">
                            <i class="fa fa-cubes"></i> Harga Material
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $tab === 'jasa' ? 'active' : '' }}"
                           id="tab-jasa-link"
                           data-toggle="tab" href="#tab-jasa" role="tab"
                           aria-selected="{{ $tab === 'jasa' ? 'true' : 'false' }}">
                            <i class="fa fa-hand-holding-dollar"></i> Jasa Tukang Saya
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="anggaranTabsContent">

                    {{-- ════════════════════════════════════════════ --}}
                    {{-- TAB 1 : HARGA MATERIAL                       --}}
                    {{-- ════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $tab === 'material' ? 'show active' : '' }}"
                         id="tab-material" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-cubes text-primary mr-1"></i> Daftar Harga Material
                            </h6>
                            <!-- Fitur Tambah dinonaktifkan (Hanya Admin PU) -->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-material">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Material</th>
                                        <th>Satuan</th>
                                        <th>Harga (Rp)</th>
                                        <th>Tanggal Berlaku</th>
                                        <th>Keterangan</th>
                                        <!-- Aksi dihapus -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hargaMaterial as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->material->nama_material }}</td>
                                        <td>{{ $item->material->satuan }}</td>
                                        <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d/m/Y') }}</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <!-- Aksi dihapus -->
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data harga material.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- /TAB 1 --}}

                    {{-- ════════════════════════════════════════════ --}}
                    {{-- TAB 2 : HARGA PEKERJAAN                     --}}
                    {{-- ════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $tab === 'pekerjaan' ? 'show active' : '' }}"
                         id="tab-pekerjaan" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-wrench text-success mr-1"></i> Daftar Pekerjaan & Komposisi
                            </h6>
                            <button class="btn btn-success btn-sm"
                                    data-toggle="modal" data-target="#createPekerjaanModal">
                                <i class="fa fa-plus"></i> Tambah Pekerjaan
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-pekerjaan">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kategori</th>
                                        <th>Nama Pekerjaan</th>
                                        <th>Satuan</th>
                                        <th>Harga (Rp)</th>
                                        <th>Komposisi Material</th>
                                        <th>Keterangan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pekerjaans as $key => $item)
                                    @php $hargaTerbaru = $item->hargaPekerjaans->sortByDesc('tanggal_berlaku')->first(); @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
                                        <td>{{ $item->nama_pekerjaan }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td class="text-right">{{ $hargaTerbaru ? number_format($hargaTerbaru->harga, 0, ',', '.') : '-' }}</td>
                                        <td>
                                            @if($item->materials->count() > 0)
                                                <ul class="pl-3 mb-0">
                                                @foreach($item->materials as $mat)
                                                    <li><small>{{ $mat->material->nama_material ?? 'Material' }} ({{ floatval($mat->qty) }} {{ $mat->material->satuan ?? '' }})</small></li>
                                                @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted"><small>-</small></span>
                                            @endif
                                        </td>
                                        <td>{{ $item->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-warning btn-xs btn-edit-pekerjaan"
                                                    data-id="{{ $item->id }}"
                                                    data-kategori="{{ $item->kategori_pekerjaan_id }}"
                                                    data-nama="{{ $item->nama_pekerjaan }}"
                                                    data-satuan="{{ $item->satuan }}"
                                                    data-deskripsi="{{ $item->deskripsi }}"
                                                    data-harga="{{ $hargaTerbaru ? (int)$hargaTerbaru->harga : '' }}"
                                                    data-tanggal="{{ $hargaTerbaru ? \Carbon\Carbon::parse($hargaTerbaru->tanggal_berlaku)->format('Y-m-d') : date('Y-m-d') }}"
                                                    data-materials="{{ json_encode($item->materials->map(function($m) { return ['material_id' => $m->material_id, 'qty' => (float)$m->qty]; })) }}"
                                                    data-toggle="modal"
                                                    data-target="#editPekerjaanModal"
                                                    title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <form action="{{ route('tukang.anggaran.pekerjaan.destroy', $item->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-xs btn-delete" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada data pekerjaan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- /TAB 2 --}}

                    {{-- ════════════════════════════════════════════ --}}
                    {{-- TAB 3 : JASA TUKANG SAYA                    --}}
                    {{-- ════════════════════════════════════════════ --}}
                    <div class="tab-pane fade {{ $tab === 'jasa' ? 'show active' : '' }}"
                         id="tab-jasa" role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 font-weight-bold">
                                <i class="fa fa-hand-holding-dollar text-warning mr-1"></i> Daftar Harga Jasa Tukang Saya
                            </h6>
                            <button class="btn btn-warning btn-sm"
                                    data-toggle="modal" data-target="#createJasaModal">
                                <i class="fa fa-plus"></i> Tambah Harga Jasa
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="table-jasa">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Jasa</th>
                                        <th>Harga (Rp)</th>
                                        <th>Deskripsi</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hargaJasa as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->nama_jasa }}</td>
                                        <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ $item->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-warning btn-xs btn-edit-jasa"
                                                    data-id="{{ $item->id }}"
                                                    data-nama_jasa="{{ $item->nama_jasa }}"
                                                    data-harga="{{ $item->harga }}"
                                                    data-deskripsi="{{ $item->deskripsi }}"
                                                    data-toggle="modal"
                                                    data-target="#editJasaModal"
                                                    title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <form action="{{ route('tukang.anggaran.jasa.destroy', $item->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-xs btn-delete" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada data harga jasa tukang.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- /TAB 3 --}}

                </div>{{-- /tab-content --}}
            </div>{{-- /ibox-body --}}
        </div>{{-- /ibox --}}
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- SEMUA MODAL DILETAKKAN DI LUAR TABEL/TAB — mencegah konflik render HTML   --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}

<!-- Modals for Material removed because it is read-only for Tukang -->

{{-- ─── CREATE: Pekerjaan ─── --}}
<div class="modal fade" id="createPekerjaanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('tukang.anggaran.pekerjaan.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-wrench mr-1 text-success"></i> Tambah Pekerjaan & Komposisi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-control" name="kategori_pekerjaan_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pekerjaan" class="form-control" placeholder="cth: Pasang Keramik" required>
                            </div>
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" class="form-control" placeholder="cth: m2" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Upah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Berlaku <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_berlaku" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="font-weight-bold">Komposisi Material (Opsional)</h6>
                    <table class="table table-sm table-bordered" id="create-material-table">
                        <thead class="bg-light">
                            <tr>
                                <th>Material</th>
                                <th width="25%">Kuantitas Dasar</th>
                                <th width="10%" class="text-center">
                                    <button type="button" class="btn btn-sm btn-success" id="btn-add-create-material"><i class="fa fa-plus"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris dinamis akan masuk ke sini -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ─── EDIT: Pekerjaan (satu modal, diisi via JS) ─── --}}
<div class="modal fade" id="editPekerjaanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formEditPekerjaan" action="" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-pencil mr-1 text-warning"></i> Edit Pekerjaan & Komposisi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-control" name="kategori_pekerjaan_id" id="edit_pekerjaan_kategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pekerjaan" id="edit_pekerjaan_nama" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="satuan" id="edit_pekerjaan_satuan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" id="edit_pekerjaan_deskripsi" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Upah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" id="edit_pekerjaan_harga" class="form-control" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Berlaku <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_berlaku" id="edit_pekerjaan_tanggal" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="font-weight-bold">Komposisi Material (Opsional)</h6>
                    <table class="table table-sm table-bordered" id="edit-material-table">
                        <thead class="bg-light">
                            <tr>
                                <th>Material</th>
                                <th width="25%">Kuantitas Dasar</th>
                                <th width="10%" class="text-center">
                                    <button type="button" class="btn btn-sm btn-success" id="btn-add-edit-material"><i class="fa fa-plus"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris dinamis diisi oleh JS -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ─── CREATE: Harga Jasa Tukang ─── --}}
<div class="modal fade" id="createJasaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="{{ route('tukang.anggaran.jasa.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-hand-holding-dollar mr-1 text-warning"></i> Tambah Harga Jasa Tukang</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jasa" class="form-control"
                               placeholder="cth: Harian, Borongan per m2" required>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ─── EDIT: Harga Jasa Tukang (satu modal, diisi via JS) ─── --}}
<div class="modal fade" id="editJasaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="formEditJasa" action="" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-pencil mr-1 text-warning"></i> Edit Harga Jasa Tukang</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Jasa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_jasa" id="edit_jasa_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="edit_jasa_harga" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="edit_jasa_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('css')
<style>
    /* Force hide inactive tabs to prevent visual bleeding */
    .tab-content > .tab-pane:not(.active) {
        display: none !important;
    }
</style>
@endpush

@push('js')
<script>
$(document).ready(function () {
    // ── Fix Bootstrap Tabs overlapping / bleeding ──
    $('a[data-toggle="tab"]').on('hide.bs.tab', function (e) {
        // Forcefully hide the previous tab pane
        var previousTarget = $(e.target).attr('href');
        $(previousTarget).removeClass('show active');
    });

    // ── DataTable: inisialisasi lazy (hanya saat tab dibuka) ────────────────
    var dtMaterial  = null;
    var dtPekerjaan = null;
    var dtJasa      = null;

    function initDT(tableId) {
        return $(tableId).DataTable({
            pageLength: 25,
            responsive: true,
            autoWidth: false,
            language: { emptyTable: 'Belum ada data.' }
        });
    }

    var activeTab = '{{ $tab }}';
    if (activeTab === 'material')  dtMaterial  = initDT('#table-material');
    if (activeTab === 'pekerjaan') dtPekerjaan = initDT('#table-pekerjaan');
    if (activeTab === 'jasa')      dtJasa      = initDT('#table-jasa');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        if (target === '#tab-material') {
            if (!dtMaterial)  dtMaterial  = initDT('#table-material');
            else dtMaterial.columns.adjust().responsive.recalc();
        }
        if (target === '#tab-pekerjaan') {
            if (!dtPekerjaan) dtPekerjaan = initDT('#table-pekerjaan');
            else dtPekerjaan.columns.adjust().responsive.recalc();
        }
        if (target === '#tab-jasa') {
            if (!dtJasa)      dtJasa      = initDT('#table-jasa');
            else dtJasa.columns.adjust().responsive.recalc();
        }
    });

    // ── Modal Edit: Harga Material — isi field dari data-* attribute ─────────
    $('#editMaterialModal').on('show.bs.modal', function (e) {
        var btn   = $(e.relatedTarget);
        var id    = btn.data('id');
        var form  = $('#formEditMaterial');

        form.attr('action', '/tukang/anggaran/material/' + id);
        $('#edit_material_id').val(btn.data('material_id'));
        $('#edit_material_harga').val(btn.data('harga'));
        $('#edit_material_tanggal').val(btn.data('tanggal'));
        $('#edit_material_keterangan').val(btn.data('keterangan'));
    });

    // ── Modal Edit: Pekerjaan & Komposisi ──────────────────────────────────────────
    $('#editPekerjaanModal').on('show.bs.modal', function (e) {
        var btn  = $(e.relatedTarget);
        var id   = btn.data('id');
        var form = $('#formEditPekerjaan');

        form.attr('action', '/tukang/anggaran/pekerjaan/' + id);
        $('#edit_pekerjaan_kategori').val(btn.data('kategori'));
        $('#edit_pekerjaan_nama').val(btn.data('nama'));
        $('#edit_pekerjaan_satuan').val(btn.data('satuan'));
        $('#edit_pekerjaan_deskripsi').val(btn.data('deskripsi'));
        $('#edit_pekerjaan_harga').val(btn.data('harga'));
        $('#edit_pekerjaan_tanggal').val(btn.data('tanggal'));
        
        // Render existing materials
        var materials = btn.data('materials');
        var tbody = $('#edit-material-table tbody');
        tbody.empty();
        
        if (materials && materials.length > 0) {
            materials.forEach(function(mat, index) {
                var newRow = `
                <tr>
                    <td>
                        <select name="materials[${editMatIndex}][material_id]" class="form-control form-control-sm" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach($materials as $m)
                            <option value="{{ $m->id }}" ${mat.material_id == {{ $m->id }} ? 'selected' : ''}>{{ $m->nama_material }} ({{ $m->satuan }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" step="0.0001" name="materials[${editMatIndex}][qty]" class="form-control form-control-sm" value="${mat.qty}" required></td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-material"><i class="fa fa-times"></i></button></td>
                </tr>`;
                tbody.append(newRow);
                editMatIndex++;
            });
        }
    });

    // ── Dynamic Material Rows ──────────────────────────────────────────────
    var createMatIndex = 0;
    $('#btn-add-create-material').click(function() {
        var newRow = `
        <tr>
            <td>
                <select name="materials[${createMatIndex}][material_id]" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Material --</option>
                    @foreach($materials as $m)
                    <option value="{{ $m->id }}">{{ $m->nama_material }} ({{ $m->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.0001" name="materials[${createMatIndex}][qty]" class="form-control form-control-sm" value="1" required></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-material"><i class="fa fa-times"></i></button></td>
        </tr>`;
        $('#create-material-table tbody').append(newRow);
        createMatIndex++;
    });

    var editMatIndex = 100; // start higher to avoid conflicts if needed
    $('#btn-add-edit-material').click(function() {
        var newRow = `
        <tr>
            <td>
                <select name="materials[${editMatIndex}][material_id]" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Material --</option>
                    @foreach($materials as $m)
                    <option value="{{ $m->id }}">{{ $m->nama_material }} ({{ $m->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.0001" name="materials[${editMatIndex}][qty]" class="form-control form-control-sm" value="1" required></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-material"><i class="fa fa-times"></i></button></td>
        </tr>`;
        $('#edit-material-table tbody').append(newRow);
        editMatIndex++;
    });

    $(document).on('click', '.btn-remove-material', function() {
        $(this).closest('tr').remove();
    });

    // ── Modal Edit: Harga Jasa Tukang ────────────────────────────────────────
    $('#editJasaModal').on('show.bs.modal', function (e) {
        var btn  = $(e.relatedTarget);
        var id   = btn.data('id');
        var form = $('#formEditJasa');

        form.attr('action', '/tukang/anggaran/jasa/' + id);
        $('#edit_jasa_nama').val(btn.data('nama_jasa'));
        $('#edit_jasa_harga').val(btn.data('harga'));
        $('#edit_jasa_deskripsi').val(btn.data('deskripsi'));
    });

    // ── Konfirmasi hapus (SweetAlert) ────────────────────────────────────────
    $(document).on('click', '.btn-delete', function () {
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });

});
</script>
@endpush
