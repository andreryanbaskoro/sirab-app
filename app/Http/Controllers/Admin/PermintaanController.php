<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permintaan;
use App\Enums\PermintaanStatus;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Permintaan::with(['konsumen', 'tukang', 'tipeRumah'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_permintaan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('konsumen', fn($q2) => $q2->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->tanggal_dari) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->tanggal_sampai) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $data = $query->paginate(15)->appends($request->all());
        $statuses = PermintaanStatus::cases();

        return view('admin.permintaan.index', compact('data', 'statuses'));
    }

    public function show(Permintaan $permintaan)
    {
        $permintaan->load(['konsumen.profile', 'tukang.profile', 'tipeRumah', 'rab.details', 'kontrak', 'validasis']);
        return view('admin.permintaan.show', compact('permintaan'));
    }
}
