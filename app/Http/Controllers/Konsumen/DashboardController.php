<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Models\Rab;
use App\Models\Kontrak;
use App\Enums\PermintaanStatus;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalPermintaan = Permintaan::forKonsumen($user->id)->count();
        $permintaanAktif = Permintaan::forKonsumen($user->id)
            ->whereNotIn('status', ['selesai', 'ditolak_tukang', 'ditolak_konsumen'])
            ->count();

        $totalRab = Rab::whereHas('permintaan', fn($q) => $q->where('konsumen_id', $user->id))->count();

        $totalKontrak = Kontrak::where('konsumen_id', $user->id)->count();

        $permintaanTerbaru = Permintaan::forKonsumen($user->id)
            ->with(['tukang.profile', 'tipeRumah', 'rab'])
            ->latest()
            ->take(5)
            ->get();

        return view('konsumen.dashboard', compact(
            'totalPermintaan',
            'permintaanAktif',
            'totalRab',
            'totalKontrak',
            'permintaanTerbaru'
        ));
    }
}
