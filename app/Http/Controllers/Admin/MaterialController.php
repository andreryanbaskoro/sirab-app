<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $data = Material::with('hargaMaterials')->latest()->paginate(10);
        return view('admin.material.index', compact('data'));
    }

    public function create()
    {
        return view('admin.material.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_material' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
        ]);

        $material = Material::create($request->only('nama_material', 'satuan', 'deskripsi'));
        
        $material->hargaMaterials()->create([
            'harga' => $request->harga,
            'tanggal_berlaku' => $request->tanggal_berlaku,
        ]);

        return redirect()->route('admin.material.index')->with('success', 'Material beserta harga berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        $hargaTerbaru = $material->hargaTerbaru();
        return view('admin.material.edit', compact('material', 'hargaTerbaru'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'nama_material' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
        ]);

        $material->update($request->only('nama_material', 'satuan', 'deskripsi'));

        $hargaTerbaru = $material->hargaTerbaru();

        if (!$hargaTerbaru || $hargaTerbaru->harga != $request->harga || $hargaTerbaru->tanggal_berlaku != $request->tanggal_berlaku) {
            $material->hargaMaterials()->create([
                'harga' => $request->harga,
                'tanggal_berlaku' => $request->tanggal_berlaku,
            ]);
        }

        return redirect()->route('admin.material.index')->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $material->delete();
        return redirect()->route('admin.material.index')->with('success', 'Material berhasil dihapus.');
    }
}
