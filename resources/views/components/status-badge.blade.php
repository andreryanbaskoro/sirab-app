@props(['status'])

@php
    $color = 'secondary';
    $label = '-';

    if ($status instanceof \App\Enums\PermintaanStatus || 
        $status instanceof \App\Enums\RabStatus || 
        $status instanceof \App\Enums\KontrakStatus) {
        $color = $status->color();
        $label = $status->label();
    } elseif (is_string($status)) {
        // Fallback for raw string
        $status = strtolower($status);
        if (in_array($status, ['aktif', 'disetujui', 'selesai', 'kontrak_aktif'])) {
            $color = 'success';
        } elseif (in_array($status, ['pending', 'draft'])) {
            $color = 'secondary';
        } elseif (in_array($status, ['menunggu_persetujuan'])) {
            $color = 'warning';
        } elseif (in_array($status, ['diterima_tukang', 'disusun_rab'])) {
            $color = 'info';
        } elseif (str_contains($status, 'tolak') || str_contains($status, 'batal')) {
            $color = 'danger';
        }
        $label = ucwords(str_replace('_', ' ', $status));
    }
@endphp

<span class="badge badge-{{ $color }} px-2 py-1">{{ $label }}</span>
