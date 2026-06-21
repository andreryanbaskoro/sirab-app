<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $statuses = [
            PermintaanStatus::SELESAI,
            PermintaanStatus::DITOLAK_TUKANG,
            PermintaanStatus::DITOLAK_KONSUMEN
        ];

        $data = Permintaan::forKonsumen(Auth::id())
            ->whereIn('status', $statuses)
            ->with(['tukang.profile', 'tipeRumah'])
            ->latest()
            ->paginate(10);

        return view('konsumen.riwayat.index', compact('data'));
    }
}
