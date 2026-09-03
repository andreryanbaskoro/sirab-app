<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HargaJasaKonsultan;
use App\Models\User;
use Illuminate\Http\Request;

class HargaJasaKonsultanController extends Controller
{
    public function index()
    {
        $data = HargaJasaKonsultan::with('konsultan')->latest()->paginate(10);
        return view('admin.harga-jasa-konsultan.index', compact('data'));
    }

    public function create()
    {
        $konsultans = User::role('konsultan')->orderBy('name')->get();
        return view('admin.harga-jasa-konsultan.create', compact('konsultans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_jasa' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        HargaJasaKonsultan::create($request->all());

        return redirect()->route('admin.harga-jasa-konsultan.index')->with('success', 'Harga jasa konsultan berhasil ditambahkan.');
    }

    public function edit(HargaJasaKonsultan $hargaJasaKonsultan)
    {
        $konsultans = User::role('konsultan')->orderBy('name')->get();
        return view('admin.harga-jasa-konsultan.edit', compact('hargaJasaKonsultan', 'konsultans'));
    }

    public function update(Request $request, HargaJasaKonsultan $hargaJasaKonsultan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_jasa' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $hargaJasaKonsultan->update($request->all());

        return redirect()->route('admin.harga-jasa-konsultan.index')->with('success', 'Harga jasa konsultan berhasil diperbarui.');
    }

    public function destroy(HargaJasaKonsultan $hargaJasaKonsultan)
    {
        $hargaJasaKonsultan->delete();
        return redirect()->route('admin.harga-jasa-konsultan.index')->with('success', 'Harga jasa konsultan berhasil dihapus.');
    }
}
