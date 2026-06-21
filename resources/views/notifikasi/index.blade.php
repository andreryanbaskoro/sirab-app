@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title"><i class="fa-solid fa-bell mr-2 text-primary"></i> Pusat Notifikasi</div>
                <div class="ibox-tools">
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-check-double"></i> Tandai Semua Dibaca</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="ibox-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifikasi as $notif)
                        <div class="list-group-item d-flex justify-content-between align-items-center {{ empty($notif->read_at) ? 'bg-light font-weight-bold' : '' }}">
                            <div class="d-flex w-100">
                                <div class="mr-3 mt-1">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1 {{ empty($notif->read_at) ? 'text-primary' : '' }}">{{ $notif->data['title'] ?? 'Pemberitahuan Sistem' }}</h6>
                                        <small class="text-muted"><i class="fa-regular fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notif->data['message'] ?? '' }}</p>
                                    
                                    @if(empty($notif->read_at))
                                        <a href="{{ route('notifikasi.read', $notif->id) }}" class="btn btn-sm btn-primary mt-2">Buka & Tandai Dibaca</a>
                                    @elseif(isset($notif->data['url']) && $notif->data['url'] !== '#')
                                        <a href="{{ $notif->data['url'] }}" class="btn btn-sm btn-outline-secondary mt-2">Lihat Detail</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="fa-regular fa-bell-slash fa-4x mb-3 text-light"></i>
                            <h5>Belum ada Notifikasi</h5>
                            <p>Anda belum menerima pemberitahuan apa pun dari sistem.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifikasi->hasPages())
            <div class="ibox-footer d-flex justify-content-center">
                {{ $notifikasi->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
