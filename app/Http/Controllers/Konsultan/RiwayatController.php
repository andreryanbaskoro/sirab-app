<?php

namespace App\Http\Controllers\Konsultan;

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
            PermintaanStatus::DITOLAK_KONSULTAN,
            PermintaanStatus::DITOLAK_KONSUMEN
        ];

        $data = Permintaan::forKonsultan(Auth::id())
            ->whereIn('status', $statuses)
            ->with(['konsumen.profile', 'tipeRumah'])
            ->latest()
            ->paginate(10);

        return view('konsultan.riwayat.index', compact('data'));
    }
}
