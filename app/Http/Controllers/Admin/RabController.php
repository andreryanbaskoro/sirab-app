<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use App\Enums\RabStatus;
use Illuminate\Http\Request;

class RabController extends Controller
{
    public function index(Request $request)
    {
        $query = Rab::with(['permintaan.konsumen', 'permintaan.tukang', 'permintaan.tipeRumah'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('nomor_rab', 'like', '%' . $request->search . '%');
        }

        $data = $query->paginate(15)->appends($request->all());
        $statuses = RabStatus::cases();

        return view('admin.rab.index', compact('data', 'statuses'));
    }

    public function show(Rab $rab)
    {
        $rab->load(['permintaan.konsumen.profile', 'permintaan.tukang.profile', 'permintaan.tipeRumah', 'details']);
        return view('admin.rab.show', compact('rab'));
    }
}
