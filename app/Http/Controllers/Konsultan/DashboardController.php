<?php

namespace App\Http\Controllers\Konsultan;

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

        $total_permintaan = Permintaan::forKonsultan($user->id)->count();
        
        $permintaan_pending = Permintaan::forKonsultan($user->id)
            ->where('status', PermintaanStatus::PENDING)
            ->count();

        $total_rab = Rab::where('konsultan_id', $user->id)->count();

        $kontrak_aktif = Kontrak::where('konsultan_id', $user->id)
            ->where('status', 'aktif')
            ->count();

        $permintaan_terbaru = Permintaan::forKonsultan($user->id)
            ->with(['konsumen', 'tipeRumah'])
            ->latest()
            ->take(5)
            ->get();

        return view('konsultan.dashboard', compact(
            'total_permintaan',
            'permintaan_pending',
            'total_rab',
            'kontrak_aktif',
            'permintaan_terbaru'
        ));
    }
}
