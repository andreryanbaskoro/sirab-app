<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanController extends Controller
{
    public function __construct(private NotificationService $notifService) {}

    public function index(Request $request)
    {
        $query = Permintaan::forTukang(Auth::id())
            ->with(['konsumen.profile', 'tipeRumah', 'rab'])
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->paginate(10)->appends($request->all());
        $statuses = PermintaanStatus::cases();

        return view('tukang.permintaan.index', compact('data', 'statuses'));
    }

    public function show(Permintaan $permintaan)
    {
        $this->authorize('view-tukang', $permintaan);
        $permintaan->load(['konsumen.profile', 'tipeRumah', 'rab.details', 'kontrak', 'validasis']);
        return view('tukang.permintaan.show', compact('permintaan'));
    }

    public function terima(Permintaan $permintaan)
    {
        $this->authorize('view-tukang', $permintaan);

        if ($permintaan->status !== PermintaanStatus::PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending.');
        }

        $permintaan->update(['status' => PermintaanStatus::DITERIMA_TUKANG]);

        try {
            $this->notifService->notifikasiPermintaanDiterima($permintaan);
        } catch (\Exception $e) {
            // log error but don't fail
        }

        return back()->with('success', 'Permintaan berhasil diterima.');
    }

    public function tolak(Request $request, Permintaan $permintaan)
    {
        $this->authorize('view-tukang', $permintaan);
        $request->validate(['alasan_tolak' => 'required|string|max:500']);

        if ($permintaan->status !== PermintaanStatus::PENDING) {
            return back()->with('error', 'Permintaan tidak dalam status pending.');
        }

        $permintaan->update([
            'status' => PermintaanStatus::DITOLAK_TUKANG,
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        try {
            $this->notifService->notifikasiPermintaanDitolakTukang($permintaan);
        } catch (\Exception $e) {
            // log error
        }

        return back()->with('success', 'Permintaan berhasil ditolak.');
    }

    private function authorize(string $ability, Permintaan $permintaan)
    {
        if ($permintaan->tukang_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
