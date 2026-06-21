<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Enums\KontrakStatus;
use Illuminate\Http\Request;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        $query = Kontrak::with(['permintaan', 'konsumen', 'tukang'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('nomor_kontrak', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(15)->appends($request->all());
        $statuses = KontrakStatus::cases();

        return view('admin.kontrak.index', compact('data', 'statuses'));
    }

    public function show(Kontrak $kontrak)
    {
        $kontrak->load(['permintaan.tipeRumah', 'konsumen.profile', 'tukang.profile', 'rab.details']);
        return view('admin.kontrak.show', compact('kontrak'));
    }
}
