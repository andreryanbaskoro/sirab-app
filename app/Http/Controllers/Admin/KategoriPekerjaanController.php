<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPekerjaan;
use Illuminate\Http\Request;

class KategoriPekerjaanController extends Controller
{
    public function index()
    {
        $data = KategoriPekerjaan::withCount('pekerjaans')->latest()->paginate(10);
        return view('admin.kategori-pekerjaan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pekerjaans,nama_kategori',
        ]);

        KategoriPekerjaan::create($request->only('nama_kategori'));

        return back()->with('success', 'Kategori Pekerjaan berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriPekerjaan $kategoriPekerjaan)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_pekerjaans,nama_kategori,' . $kategoriPekerjaan->id,
        ]);

        $kategoriPekerjaan->update($request->only('nama_kategori'));

        return back()->with('success', 'Kategori Pekerjaan berhasil diperbarui.');
    }

    public function destroy(KategoriPekerjaan $kategoriPekerjaan)
    {
        if ($kategoriPekerjaan->pekerjaans()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh data Pekerjaan.');
        }

        $kategoriPekerjaan->delete();

        return back()->with('success', 'Kategori Pekerjaan berhasil dihapus.');
    }
}
