<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use App\Models\HargaMaterial;
use App\Models\HargaPekerjaan;
use App\Models\HargaJasaTukang;
use App\Models\Material;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggaranController extends Controller
{
    // ─── INDEX (tampilkan semua data) ────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pekerjaan');

        $hargaMaterial  = HargaMaterial::with('material')->latest()->get();
        $hargaJasa      = HargaJasaTukang::where('user_id', Auth::id())->latest()->get();

        $materials  = Material::orderBy('nama_material')->get();
        $kategoris  = \App\Models\KategoriPekerjaan::orderBy('nama_kategori')->get();
        $pekerjaans = Pekerjaan::with(['hargaPekerjaans', 'materials.material', 'kategori'])->latest()->get();

        return view('tukang.anggaran.index', compact(
            'hargaMaterial', 'hargaJasa',
            'materials', 'pekerjaans', 'tab', 'kategoris'
        ));
    }

    // ─── HARGA MATERIAL ──────────────────────────────────────────────────────
    // (Fitur CRUD Harga Material dipindahkan ke Admin PU)

    // ─── HARGA PEKERJAAN ─────────────────────────────────────────────────────

    public function storePekerjaan(Request $request)
    {
        $request->validate([
            'kategori_pekerjaan_id' => 'required|exists:kategori_pekerjaans,id',
            'nama_pekerjaan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'materials' => 'nullable|array',
        ]);

        $pekerjaan = Pekerjaan::create($request->only('kategori_pekerjaan_id', 'nama_pekerjaan', 'satuan', 'deskripsi'));
        
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

        return redirect()->route('tukang.anggaran.index', ['tab' => 'pekerjaan'])
            ->with('success', 'Pekerjaan beserta komposisi material berhasil ditambahkan.');
    }

    public function updatePekerjaan(Request $request, Pekerjaan $pekerjaan)
    {
        $request->validate([
            'kategori_pekerjaan_id' => 'required|exists:kategori_pekerjaans,id',
            'nama_pekerjaan' => 'required|string|max:100',
            'satuan' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
            'materials' => 'nullable|array',
        ]);

        $pekerjaan->update($request->only('kategori_pekerjaan_id', 'nama_pekerjaan', 'satuan', 'deskripsi'));

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

        return redirect()->route('tukang.anggaran.index', ['tab' => 'pekerjaan'])
            ->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroyPekerjaan(Pekerjaan $pekerjaan)
    {
        $pekerjaan->delete();

        return redirect()->route('tukang.anggaran.index', ['tab' => 'pekerjaan'])
            ->with('success', 'Pekerjaan berhasil dihapus.');
    }

    // ─── HARGA JASA TUKANG (milik sendiri) ───────────────────────────────────

    public function storeJasa(Request $request)
    {
        $request->validate([
            'nama_jasa'  => 'required|string|max:100',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        HargaJasaTukang::create([
            'user_id'   => Auth::id(),
            'nama_jasa' => $request->nama_jasa,
            'harga'     => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('tukang.anggaran.index', ['tab' => 'jasa'])
            ->with('success', 'Harga jasa berhasil ditambahkan.');
    }

    public function updateJasa(Request $request, HargaJasaTukang $hargaJasaTukang)
    {
        // Pastikan hanya pemilik yang bisa edit
        if ($hargaJasaTukang->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama_jasa'  => 'required|string|max:100',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        $hargaJasaTukang->update($request->only('nama_jasa', 'harga', 'deskripsi'));

        return redirect()->route('tukang.anggaran.index', ['tab' => 'jasa'])
            ->with('success', 'Harga jasa berhasil diperbarui.');
    }

    public function destroyJasa(HargaJasaTukang $hargaJasaTukang)
    {
        if ($hargaJasaTukang->user_id !== Auth::id()) {
            abort(403);
        }

        $hargaJasaTukang->delete();

        return redirect()->route('tukang.anggaran.index', ['tab' => 'jasa'])
            ->with('success', 'Harga jasa berhasil dihapus.');
    }
}
