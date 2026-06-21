<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HargaJasaTukang;
use App\Models\User;
use Illuminate\Http\Request;

class HargaJasaTukangController extends Controller
{
    public function index()
    {
        $data = HargaJasaTukang::with('tukang')->latest()->paginate(10);
        return view('admin.harga-jasa-tukang.index', compact('data'));
    }

    public function create()
    {
        $tukangs = User::role('kepala_tukang')->orderBy('name')->get();
        return view('admin.harga-jasa-tukang.create', compact('tukangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_jasa' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        HargaJasaTukang::create($request->all());

        return redirect()->route('admin.harga-jasa-tukang.index')->with('success', 'Harga jasa tukang berhasil ditambahkan.');
    }

    public function edit(HargaJasaTukang $hargaJasaTukang)
    {
        $tukangs = User::role('kepala_tukang')->orderBy('name')->get();
        return view('admin.harga-jasa-tukang.edit', compact('hargaJasaTukang', 'tukangs'));
    }

    public function update(Request $request, HargaJasaTukang $hargaJasaTukang)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_jasa' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $hargaJasaTukang->update($request->all());

        return redirect()->route('admin.harga-jasa-tukang.index')->with('success', 'Harga jasa tukang berhasil diperbarui.');
    }

    public function destroy(HargaJasaTukang $hargaJasaTukang)
    {
        $hargaJasaTukang->delete();
        return redirect()->route('admin.harga-jasa-tukang.index')->with('success', 'Harga jasa tukang berhasil dihapus.');
    }
}
