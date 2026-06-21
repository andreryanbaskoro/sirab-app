@extends('layouts.app')

@section('title', 'Detail Permintaan RAB')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Status Permintaan</div>
            </div>
            <div class="ibox-body">
                <h4 class="font-strong mb-3">{{ $permintaan->nomor_permintaan }}</h4>
                <div class="mb-4">
                    <x-status-badge :status="$permintaan->status" />
                </div>
                
                @if($permintaan->status === \App\Enums\PermintaanStatus::DITOLAK_TUKANG || $permintaan->status === \App\Enums\PermintaanStatus::DITOLAK_KONSUMEN)
                    <div class="alert alert-danger">
                        <strong>Alasan Penolakan:</strong><br>
                        {{ $permintaan->alasan_tolak ?? ($permintaan->rab->alasan_tolak ?? 'Tidak ada alasan yang diberikan.') }}
                    </div>
                @endif
                
                <h5 class="text-info mt-4 mb-3"><i class="fa fa-history"></i> Timeline</h5>
                <ul class="timeline">
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-content">
                            <strong>Permintaan Dibuat</strong><br>
                            <small class="text-muted">{{ $permintaan->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </li>
                    
                    @if(in_array($permintaan->status->value, ['diterima_tukang', 'disusun_rab', 'menunggu_persetujuan', 'disetujui', 'kontrak_aktif', 'selesai']))
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-content">
                            <strong>Diterima Tukang</strong><br>
                            <small class="text-muted">Tukang sedang menyiapkan/menyusun RAB.</small>
                        </div>
                    </li>
                    @endif
                    
                    @if(in_array($permintaan->status->value, ['menunggu_persetujuan', 'disetujui', 'kontrak_aktif', 'selesai']))
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-warning"></span>
                        <div class="timeline-content">
                            <strong>Menunggu Persetujuan</strong><br>
                            <small class="text-muted">RAB selesai disusun. Menunggu respon Anda.</small>
                        </div>
                    </li>
                    @endif
                    
                    @if(in_array($permintaan->status->value, ['disetujui', 'kontrak_aktif', 'selesai']))
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-primary"></span>
                        <div class="timeline-content">
                            <strong>Disetujui & Kontrak Aktif</strong><br>
                            <small class="text-muted">Pekerjaan dapat dimulai.</small>
                        </div>
                    </li>
                    @endif
                    
                    @if($permintaan->status->value === 'selesai')
                    <li class="timeline-item">
                        <span class="timeline-point timeline-point-success"></span>
                        <div class="timeline-content">
                            <strong>Pekerjaan Selesai</strong><br>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Informasi Kepala Tukang</div>
            </div>
            <div class="ibox-body text-center">
                <img src="{{ $permintaan->tukang?->foto_profil ?? asset('themes/assets/img/admin-avatar.png') }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                <h5 class="font-strong mb-1">{{ $permintaan->tukang?->name ?? 'Belum Pilih Tukang' }}</h5>
                <p class="text-muted"><i class="fa fa-phone"></i> {{ $permintaan->tukang?->profile?->no_hp ?? '-' }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Detail Proyek</div>
            </div>
            <div class="ibox-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%" class="bg-light font-weight-bold">Tipe Rumah</td>
                            <td>{{ $permintaan->tipeRumah->nama_tipe }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Jenis Jasa</td>
                            <td>{{ ucfirst($permintaan->jenis_jasa) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Luas Bangunan</td>
                            <td>{{ number_format($permintaan->luas_bangunan, 2, ',', '.') }} m²</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Sumber Denah</td>
                            <td>
                                @if($permintaan->sumber_denah === 'upload_sendiri')
                                    <span class="badge badge-info">Dari Konsumen</span>
                                @elseif($permintaan->sumber_denah === 'dibuatkan_tukang')
                                    <span class="badge badge-warning">Minta Dibuatkan Tukang</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Lokasi Proyek</td>
                            <td>{{ $permintaan->lokasi_proyek }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Catatan</td>
                            <td>{{ $permintaan->catatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light font-weight-bold">Dokumen Lampiran</td>
                            <td>
                                @if($permintaan->dokumen_path)
                                    @php
                                        $ext = pathinfo($permintaan->dokumen_path, PATHINFO_EXTENSION);
                                    @endphp
                                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $permintaan->dokumen_path) }}" class="img-fluid border" style="max-height: 200px;" alt="Sketsa Denah">
                                        </div>
                                    @endif
                                    <a href="{{ asset('storage/' . $permintaan->dokumen_path) }}" target="_blank" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> Lihat / Unduh Lampiran Penuh</a>
                                @else
                                    @if($permintaan->sumber_denah === 'dibuatkan_tukang')
                                        <span class="text-warning"><i class="fa fa-clock-o"></i> Menunggu Tukang Mengunggah Sketsa (RAB belum disubmit)</span>
                                    @else
                                        <span class="text-muted">Tidak ada dokumen</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($permintaan->rab)
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Detail RAB ({{ $permintaan->rab->nomor_rab }})</div>
                <div class="ibox-tools">
                    <a href="{{ route('konsumen.pembiayaan.show', $permintaan->rab->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-external-link"></i> Kelola di Menu Pembiayaan</a>
                </div>
            </div>
            <div class="ibox-body">
                
                @if($permintaan->status === \App\Enums\PermintaanStatus::MENUNGGU_PERSETUJUAN)
                <div class="alert alert-warning mb-4">
                    <h5><i class="fa fa-exclamation-triangle"></i> Menunggu Persetujuan Anda</h5>
                    <p>Kepala Tukang telah menyelesaikan penyusunan RAB. Silakan kelola (Setujui/Tolak) melalui menu <strong>Pembiayaan / Hasil RAB</strong>.</p>
                    <a href="{{ route('konsumen.pembiayaan.show', $permintaan->rab->id) }}" class="btn btn-success mt-2">Buka Menu Pembiayaan</a>
                </div>
                @endif
            
                <x-rab-table :rab="$permintaan->rab" />
                
                @if($permintaan->rab->catatan_tukang)
                <div class="mt-4">
                    <h6>Catatan dari Tukang:</h6>
                    <p class="text-muted font-italic">{{ $permintaan->rab->catatan_tukang }}</p>
                </div>
                @endif
                
            </div>
        </div>
        @endif
        
    </div>
</div>



<style>
/* Simple timeline CSS */
.timeline { list-style: none; padding: 0; position: relative; }
.timeline:before { top: 0; bottom: 0; position: absolute; content: " "; width: 2px; background-color: #e5e5e5; left: 14px; margin-left: -1px; }
.timeline-item { position: relative; margin-bottom: 20px; }
.timeline-point { position: absolute; width: 12px; height: 12px; border-radius: 50%; background: #e5e5e5; left: 8px; top: 5px; }
.timeline-point-success { background: #2ecc71; box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.2); }
.timeline-point-warning { background: #f39c12; box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.2); }
.timeline-point-primary { background: #3498db; box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2); }
.timeline-content { margin-left: 35px; }
</style>
@endsection

