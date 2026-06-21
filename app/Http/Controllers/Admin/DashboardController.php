<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permintaan;
use App\Models\Kontrak;
use App\Models\Rab;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKonsumen = User::role('konsumen')->count();
        $totalTukang = User::role('kepala_tukang')->count();
        $totalPermintaan = Permintaan::count();
        $totalKontrakAktif = Kontrak::where('status', 'aktif')->count();
        
        $nilaiTotalKontrak = Kontrak::whereIn('status', ['aktif', 'selesai'])->sum('nilai_kontrak');

        $permintaanTerbaru = Permintaan::with(['konsumen', 'tukang'])
            ->latest()
            ->take(5)
            ->get();

        $kontrakTerbaru = Kontrak::with(['konsumen', 'tukang'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalKonsumen',
            'totalTukang',
            'totalPermintaan',
            'totalKontrakAktif',
            'nilaiTotalKontrak',
            'permintaanTerbaru',
            'kontrakTerbaru'
        ));
    }
}
