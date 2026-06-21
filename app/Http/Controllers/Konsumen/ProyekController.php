<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Pembayaran;
use App\Enums\KontrakStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\KontrakService;

class ProyekController extends Controller
{
    public function __construct(private KontrakService $kontrakService) {}

    public function index()
    {
        $kontraks = Kontrak::where('konsumen_id', Auth::id())
            ->whereIn('status', [KontrakStatus::AKTIF])
            ->with(['tukang.profile', 'permintaan.tipeRumah', 'pembayarans'])
            ->latest()
            ->paginate(10);

        return view('konsumen.proyek.index', compact('kontraks'));
    }

    public function show(Kontrak $proyek)
    {
        if ($proyek->konsumen_id !== Auth::id()) abort(403);
        
        $proyek->load(['tukang.profile', 'permintaan.tipeRumah', 'pembayarans', 'rab.details']);
        return view('konsumen.proyek.show', compact('proyek'));
    }

    public function uploadPembayaran(Request $request, Kontrak $proyek)
    {
        if ($proyek->konsumen_id !== Auth::id()) abort(403);

        $request->validate([
            'termin' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1000',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $proyek->pembayarans()->create([
            'termin' => $request->termin,
            'jumlah' => $request->jumlah,
            'bukti_transfer' => $path,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu_verifikasi'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah dan menunggu verifikasi.');
    }

    public function setujuiSelesai(Kontrak $proyek)
    {
        if ($proyek->konsumen_id !== Auth::id()) abort(403);

        $this->kontrakService->completeContract($proyek);

        return redirect()->route('konsumen.proyek.index')->with('success', 'Proyek telah resmi diselesaikan!');
    }
}
