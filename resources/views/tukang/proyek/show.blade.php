@extends('layouts.app')

@section('title', 'Detail Proyek Berjalan')

@section('content')
<div class="page-heading">
    <h1 class="page-title">Detail Kontrak / Proyek</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('tukang.dashboard') }}"><i class="fa-solid fa-home"></i></a></li>
        <li class="breadcrumb-item"><a href="{{ route('tukang.proyek.index') }}">Proyek Berjalan</a></li>
        <li class="breadcrumb-item active">{{ $proyek->nomor_kontrak }}</li>
    </ol>
</div>

<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-4">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Info Proyek</div>
                </div>
                <div class="ibox-body">
                    <ul class="list-group list-group-divider list-group-full">
                        <li class="list-group-item flexbox">
                            <span>No Kontrak</span>
                            <span class="font-weight-bold">{{ $proyek->nomor_kontrak }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Konsumen</span>
                            <span>{{ $proyek->konsumen->name }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Tipe Rumah</span>
                            <span>{{ $proyek->permintaan->tipeRumah->nama_tipe }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Nilai Kontrak</span>
                            <span class="text-success font-weight-bold">Rp {{ number_format($proyek->nilai_kontrak, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Tanggal Mulai</span>
                            <span>{{ $proyek->tanggal_mulai ? \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d M Y') : '-' }}</span>
                        </li>
                        <li class="list-group-item flexbox">
                            <span>Target Selesai</span>
                            <span>{{ $proyek->tanggal_selesai ? \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d M Y') : '-' }}</span>
                        </li>
                    </ul>

                    @if(!str_contains($proyek->keterangan, '[TUKANG_MENGAJUKAN_SELESAI]'))
                    <div class="mt-4 text-center">
                        <form action="{{ route('tukang.proyek.ajukan-selesai', $proyek->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Apakah Anda yakin fisik bangunan sudah selesai dan ingin mengajukan Selesai kepada konsumen?')">
                                <i class="fa fa-flag-checkered"></i> Ajukan Penyelesaian Proyek
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="mt-4 alert alert-info text-center">
                        <i class="fa fa-info-circle"></i> Anda telah mengajukan penyelesaian. Menunggu konfirmasi dari Konsumen.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Termin & Pembayaran</div>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proyek->pembayarans as $bayar)
                                <tr>
                                    <td>{{ $bayar->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $bayar->termin }}</td>
                                    <td>Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ asset('storage/'.$bayar->bukti_transfer) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                            <i class="fa fa-image"></i> Lihat
                                        </a>
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
                                    <td>
                                        @if($bayar->status == 'menunggu_verifikasi')
                                        <form action="{{ route('tukang.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="diverifikasi">
                                            <button type="submit" class="btn btn-xs btn-success" title="Verifikasi">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('tukang.pembayaran.verifikasi', $bayar->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="btn btn-xs btn-danger" title="Tolak">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data pembayaran masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Terbayar:</th>
                                    <th colspan="4" class="text-success">Rp {{ number_format($proyek->pembayarans->where('status', 'diverifikasi')->sum('jumlah'), 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Sisa Tagihan:</th>
                                    <th colspan="4" class="text-danger">Rp {{ number_format($proyek->nilai_kontrak - $proyek->pembayarans->where('status', 'diverifikasi')->sum('jumlah'), 0, ',', '.') }}</th>
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
