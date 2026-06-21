<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;

class PekerjaanController extends Controller
{
    public function index()
    {
        $data = Pekerjaan::with(['hargaPekerjaans', 'materials.material'])->latest()->paginate(10);
        $materials = \App\Models\Material::orderBy('nama_material')->get();
        return view('admin.pekerjaan.index', compact('data', 'materials'));
    }

    public function create()
    {
        $materials = \App\Models\Material::orderBy('nama_material')->get();
        return view('admin.pekerjaan.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'materials' => 'nullable|array',
        ]);

        $pekerjaan = Pekerjaan::create($request->only('nama_pekerjaan', 'satuan', 'deskripsi'));
        
        $pekerjaan->hargaPekerjaans()->create([
            'harga' => $request->harga,
            'tanggal_berlaku' => $request->tanggal_berlaku,
        ]);

        if ($request->has('materials')) {
            foreach ($request->materials as $mat) {
                if (!empty($mat['material_id'])) {
                    $pekerjaan->materials()->create([
                        'material_id' => $mat['material_id'],
                        'qty' => $mat['qty'] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.pekerjaan.index')->with('success', 'Pekerjaan beserta komposisi material berhasil ditambahkan.');
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        $hargaTerbaru = $pekerjaan->hargaTerbaru();
        $materials = \App\Models\Material::orderBy('nama_material')->get();
        return view('admin.pekerjaan.edit', compact('pekerjaan', 'hargaTerbaru', 'materials'));
    }

    public function update(Request $request, Pekerjaan $pekerjaan)
    {
        $request->validate([
            'nama_pekerjaan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'materials' => 'nullable|array',
        ]);

        $pekerjaan->update($request->only('nama_pekerjaan', 'satuan', 'deskripsi'));

        $hargaTerbaru = $pekerjaan->hargaTerbaru();

        if (!$hargaTerbaru || $hargaTerbaru->harga != $request->harga || $hargaTerbaru->tanggal_berlaku != $request->tanggal_berlaku) {
            $pekerjaan->hargaPekerjaans()->create([
                'harga' => $request->harga,
                'tanggal_berlaku' => $request->tanggal_berlaku,
            ]);
        }

        if ($request->has('materials')) {
            $pekerjaan->materials()->delete();
            foreach ($request->materials as $mat) {
                if (!empty($mat['material_id'])) {
                    $pekerjaan->materials()->create([
                        'material_id' => $mat['material_id'],
                        'qty' => $mat['qty'] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('admin.pekerjaan.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Pekerjaan $pekerjaan)
    {
        $pekerjaan->delete();
        return redirect()->route('admin.pekerjaan.index')->with('success', 'Pekerjaan berhasil dihapus.');
    }
}
