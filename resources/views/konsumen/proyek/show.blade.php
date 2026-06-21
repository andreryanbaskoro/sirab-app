@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<div class="page-heading">
    <h1 class="page-title">Manajemen Pembayaran Proyek</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('konsumen.dashboard') }}"><i class="fa-solid fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('konsumen.proyek.index') }}">Proyek Berjalan</a></li>
        <li class="breadcrumb-item active">{{ $proyek->nomor_kontrak }}</li>
    </ol>
</div>

<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Info Kontrak & Tukang</div>
                </div>
                <div class="ibox-body">
                    <div class="text-center mb-3">
                        @php
                            $fotoPath = $proyek->tukang->profile->foto ?? null;
                            $fotoUrl = $fotoPath && file_exists(public_path('storage/' . $fotoPath)) ? asset('storage/' . $fotoPath) : asset('themes/assets/img/admin-avatar.png');
                        @endphp
                        <img src="{{ $fotoUrl }}" class="img-circle" width="100px" style="aspect-ratio:1/1; object-fit:cover;" />
                        <h5 class="font-strong mt-2 mb-0">{{ $proyek->tukang->name }}</h5>
                        <div class="text-muted">Kepala Tukang</div>
                    </div>

                    <ul class="list-group list-group-divider list-group-full">
                        <li class="list-group-item flexbox">
                            <span>No Kontrak</span>
                            <span class="font-weight-bold text-right">
                                {{ $proyek->nomor_kontrak }}<br>
                                <a href="{{ route('konsumen.pembiayaan.download-kontrak', $proyek->rab_id) }}" class="btn btn-outline-primary btn-sm mt-1" target="_blank"><i class="fa fa-download"></i> Unduh Kontrak</a>
                            </span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Nilai Proyek</span>
                            <span class="text-success font-weight-bold">Rp {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Mulai - Target Selesai</span>
                            <span>{{ $proyek->tanggal_mulai ? \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d/m/Y') : '-' }} s/d {{ $proyek->tanggal_selesai ? \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d/m/Y') : '-' }}</span>
                        </li>
                    </ul>

                    @if(str_contains($proyek->keterangan, '[TUKANG_MENGAJUKAN_SELESAI]'))
                    <div class="mt-4 alert alert-warning text-center">
                        <i class="fa fa-exclamation-triangle"></i> Tukang telah mengajukan bahwa proyek fisik sudah selesai. Mohon periksa fisik bangunan Anda.
                    </div>
                    <form action="{{ route('konsumen.proyek.selesai', $proyek->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Apakah Anda yakin menyetujui bahwa proyek ini telah SELESAI sepenuhnya?')">
                            <i class="fa fa-check-circle"></i> Setujui Selesai
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Form Pembayaran -->
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><i class="fa fa-upload"></i> Bayar Termin Baru</div>
                    <div class="ibox-tools">
                        <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
                <div class="ibox-body">
                    <form action="{{ route('konsumen.proyek.bayar', $proyek->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Keterangan Termin <span class="text-danger">*</span></label>
                                <input class="form-control" name="termin" type="text" placeholder="Misal: DP 30% atau Termin 2" required>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Jumlah Bayar (Rp) <span class="text-danger">*</span></label>
                                <input class="form-control" name="jumlah" type="number" placeholder="Nominal tanpa titik" required min="1000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bukti Transfer (Gambar) <span class="text-danger">*</span></label>
                            <input class="form-control" name="bukti_transfer" type="file" accept="image/*" required>
                            <small class="form-text text-muted">Format: jpg, jpeg, png. Maksimal 2MB.</small>
                        </div>
                        <div class="form-group">
                            <label>Catatan Opsional</label>
                            <textarea class="form-control" name="keterangan" rows="2" placeholder="Catatan untuk Tukang (opsional)"></textarea>
                        </div>
                        <button class="btn btn-success" type="submit">Kirim Pembayaran</button>
                    </form>
                </div>
            </div>

            <!-- Tabel Riwayat Pembayaran -->
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><i class="fa fa-history"></i> Riwayat Pembayaran Anda</div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-default">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Termin</th>
                                    <th>Jumlah</th>
                                    <th>Bukti</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proyek->pembayarans as $bayar)
                                <tr>
                                    <td>{{ $bayar->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $bayar->termin }}</td>
                                    <td>Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$bayar->bukti_transfer) }}" target="_blank" class="text-info">Lihat Bukti</a>
                                    </td>
                                    <td>
                                        @if($bayar->status == 'menunggu_verifikasi')
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($bayar->status == 'diverifikasi')
                                            <span class="badge badge-success">Diverifikasi</span>
                                        @else
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Anda belum melakukan pembayaran apapun.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Diverifikasi:</th>
                                    <th colspan="3" class="text-success">Rp {{ number_format($proyek->pembayarans->where('status', 'diverifikasi')->sum('jumlah'), 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Sisa Tagihan:</th>
                                    <th colspan="3" class="text-danger">Rp {{ number_format($proyek->nilai_kontrak - $proyek->pembayarans->where('status', 'diverifikasi')->sum('jumlah'), 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
