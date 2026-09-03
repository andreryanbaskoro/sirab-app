<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permintaan;
use App\Models\Rab;
use App\Models\Kontrak;
use App\Notifications\SystemNotification;

class NotificationService
{
    public function notifikasiPermintaanBaru(Permintaan $permintaan): void
    {
        $konsultan = $permintaan->konsultan;
        $url = route('konsultan.permintaan.show', $permintaan->id);
        $konsultan->notify(new SystemNotification(
            'Permintaan RAB Baru',
            $permintaan->konsumen->name . ' mengirimkan permintaan pembuatan RAB baru.',
            $url
        ));
    }

    public function notifikasiPermintaanDiterima(Permintaan $permintaan): void
    {
        $konsumen = $permintaan->konsumen;
        $url = route('konsumen.permintaan.show', $permintaan->id);
        $konsumen->notify(new SystemNotification(
            'Permintaan Diterima',
            'Permintaan Anda telah diterima oleh Konsultan (' . $permintaan->konsultan->name . ').',
            $url
        ));
    }

    public function notifikasiPermintaanDitolakKonsultan(Permintaan $permintaan): void
    {
        $konsumen = $permintaan->konsumen;
        $url = route('konsumen.permintaan.show', $permintaan->id);
        $konsumen->notify(new SystemNotification(
            'Permintaan Ditolak',
            'Mohon maaf, permintaan Anda ditolak oleh Konsultan.',
            $url
        ));
    }

    public function notifikasiRabMenungguPersetujuan(Rab $rab): void
    {
        $konsumen = $rab->permintaan->konsumen;
        $url = route('konsumen.permintaan.show', $rab->permintaan->id);
        $konsumen->notify(new SystemNotification(
            'RAB Menunggu Persetujuan',
            'Konsultan telah menyelesaikan draft RAB. Silakan review dan berikan persetujuan Anda.',
            $url
        ));
    }

    public function notifikasiRabDisetujui(Rab $rab): void
    {
        $konsultan = $rab->permintaan->konsultan;
        $url = route('konsultan.rab.show', $rab->id);
        $konsultan->notify(new SystemNotification(
            'RAB Disetujui',
            'Selamat! RAB Anda telah disetujui oleh Konsumen dan Kontrak Kerja otomatis dibuat.',
            $url
        ));

        // Notify admins
        $admins = User::role('admin_pu')->get();
        foreach ($admins as $admin) {
            $admin->notify(new SystemNotification(
                'Kontrak Baru Diterbitkan',
                'Kontrak baru diterbitkan untuk RAB ' . $rab->nomor_rab,
                route('admin.kontrak.index')
            ));
        }
    }

    public function notifikasiRabDitolak(Rab $rab): void
    {
        $konsultan = $rab->permintaan->konsultan;
        $url = route('konsultan.rab.show', $rab->id);
        $konsultan->notify(new SystemNotification(
            'RAB Ditolak Revisi',
            'RAB Anda ditolak oleh Konsumen dengan catatan. Silakan lakukan revisi.',
            $url
        ));
    }
}
