<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Pembayaran;
use App\Enums\KontrakStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\KontrakService;

class ProyekController extends Controller
{
    public function index()
    {
        $kontraks = Kontrak::where('tukang_id', Auth::id())
            ->whereIn('status', [KontrakStatus::AKTIF])
            ->with(['konsumen.profile', 'permintaan.tipeRumah', 'pembayarans'])
            ->latest()
            ->paginate(10);

        return view('tukang.proyek.index', compact('kontraks'));
    }

    public function show(Kontrak $proyek)
    {
        if ($proyek->tukang_id !== Auth::id()) abort(403);
        
        $proyek->load(['konsumen.profile', 'permintaan.tipeRumah', 'pembayarans', 'rab.details']);
        return view('tukang.proyek.show', compact('proyek'));
    }

    public function verifikasiPembayaran(Request $request, Pembayaran $pembayaran)
    {
        if ($pembayaran->kontrak->tukang_id !== Auth::id()) abort(403);

        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak'
        ]);

        $pembayaran->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pembayaran berhasil diupdate!');
    }

    public function ajukanSelesai(Kontrak $proyek)
    {
        if ($proyek->tukang_id !== Auth::id()) abort(403);

        // Tandai bahwa tukang sudah mengajukan penyelesaian
        // Kita bisa pakai kolom keterangan atau ubah status ke 'menunggu_penyelesaian' (tapi enum kita cuma ada AKTIF, SELESAI, BATAL).
        // Mari kita buat status string sementara di keterangan.
        $keteranganLama = $proyek->keterangan;
        $proyek->update([
            'keterangan' => $keteranganLama . "\n[TUKANG_MENGAJUKAN_SELESAI]"
        ]);

        return back()->with('success', 'Pengajuan penyelesaian proyek telah dikirim ke konsumen.');
    }
}
