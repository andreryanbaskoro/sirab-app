<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipeRumah;
use Illuminate\Http\Request;

class TipeRumahController extends Controller
{
    public function index()
    {
        $data = TipeRumah::withCount('permintaans')->latest()->paginate(10);
        return view('admin.tipe-rumah.index', compact('data'));
    }

    public function create()
    {
        return view('admin.tipe-rumah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tipe' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'luas' => 'required|numeric|min:0',
        ]);

        TipeRumah::create($request->all());

        return redirect()->route('admin.tipe-rumah.index')->with('success', 'Tipe rumah berhasil ditambahkan.');
    }

    public function edit(TipeRumah $tipeRumah)
    {
        return view('admin.tipe-rumah.edit', compact('tipeRumah'));
    }

    public function update(Request $request, TipeRumah $tipeRumah)
    {
        $request->validate([
            'nama_tipe' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'luas' => 'required|numeric|min:0',
        ]);

        $tipeRumah->update($request->all());

        return redirect()->route('admin.tipe-rumah.index')->with('success', 'Tipe rumah berhasil diperbarui.');
    }

    public function destroy(TipeRumah $tipeRumah)
    {
        $tipeRumah->delete();
        return redirect()->route('admin.tipe-rumah.index')->with('success', 'Tipe rumah berhasil dihapus.');
    }
}
