<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;
use App\Enums\RabStatus;
use App\Services\KontrakService;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembiayaanController extends Controller
{
    public function __construct(
        private KontrakService $kontrakService,
        private NotificationService $notifService
    ) {}

    public function index()
    {
        $data = Rab::whereHas('permintaan', function($q) {
            $q->where('konsumen_id', Auth::id());
        })
        ->with(['permintaan.tipeRumah', 'tukang.profile'])
        ->latest()
        ->paginate(10);

        return view('konsumen.pembiayaan.index', compact('data'));
    }

    public function show(Rab $rab)
    {
        if ($rab->permintaan->konsumen_id !== Auth::id()) abort(403);
        $rab->load(['permintaan.tipeRumah', 'tukang.profile', 'details', 'kontrak']);
        return view('konsumen.pembiayaan.show', compact('rab'));
    }

    public function setujui(Rab $rab)
    {
        $permintaan = $rab->permintaan;
        if ($permintaan->konsumen_id !== Auth::id()) abort(403);

        if ($rab->status !== RabStatus::MENUNGGU_PERSETUJUAN) {
            return back()->with('error', 'RAB belum siap untuk disetujui.');
        }

        // Approve RAB
        $rab->update(['status' => RabStatus::DISETUJUI]);
        $permintaan->update(['status' => PermintaanStatus::DISETUJUI]);

        // Create contract automatically
        $kontrak = $this->kontrakService->createContract($rab);
        $this->kontrakService->activateContract($kontrak, [
            'tanggal_mulai' => now()->toDateString(),
            'tanggal_selesai' => now()->addMonths(3)->toDateString(),
        ]);

        try {
            $this->notifService->notifikasiRabDisetujui($rab);
        } catch (\Exception $e) {}

        return back()->with('success', 'RAB disetujui! Kontrak kerja dengan nomor ' . $kontrak->nomor_kontrak . ' telah dibuat.');
    }

    public function tolak(Request $request, Rab $rab)
    {
        $permintaan = $rab->permintaan;
        if ($permintaan->konsumen_id !== Auth::id()) abort(403);
        $request->validate(['alasan_tolak' => 'required|string|max:500']);

        if ($rab->status !== RabStatus::MENUNGGU_PERSETUJUAN) {
            return back()->with('error', 'Tidak dapat menolak RAB saat ini.');
        }

        $rab->update(['status' => RabStatus::DITOLAK, 'catatan' => $request->alasan_tolak]);
        $permintaan->update(['status' => PermintaanStatus::DITOLAK_KONSUMEN]);

        try {
            $this->notifService->notifikasiRabDitolak($rab);
        } catch (\Exception $e) {}

        return back()->with('success', 'RAB berhasil ditolak.');
    }

    public function downloadRab(Rab $rab)
    {
        if ($rab->permintaan->konsumen_id !== Auth::id()) abort(403);

        $rab->load(['permintaan.konsumen.profile', 'permintaan.tukang.profile', 'permintaan.tipeRumah', 'details']);

        $pdf = Pdf::loadView('pdf.rab', compact('rab'))->setPaper('a4', 'portrait');
        return $pdf->download('RAB-' . $rab->nomor_rab . '.pdf');
    }

    public function downloadKontrak(Rab $rab)
    {
        if ($rab->permintaan->konsumen_id !== Auth::id()) abort(403);

        $kontrak = $rab->kontrak;
        if (!$kontrak) return back()->with('error', 'Kontrak belum tersedia.');

        $kontrak->load(['permintaan.tipeRumah', 'konsumen.profile', 'tukang.profile', 'rab']);

        $pdf = Pdf::loadView('pdf.kontrak', compact('kontrak'))->setPaper('a4', 'portrait');
        return $pdf->download('Kontrak-' . $kontrak->nomor_kontrak . '.pdf');
    }
}
