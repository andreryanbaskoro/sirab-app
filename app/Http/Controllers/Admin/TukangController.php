<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TukangController extends Controller
{
    public function index()
    {
        $data = User::role('kepala_tukang')->with('profile')->latest()->paginate(10);
        return view('admin.tukang.index', compact('data'));
    }

    public function create()
    {
        return view('admin.tukang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'pengalaman' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kepala_tukang',
            'status_aktif' => true,
        ]);
        
        $user->assignRole('kepala_tukang');

        Profile::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'pengalaman' => $request->pengalaman,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.tukang.index')->with('success', 'Kepala tukang berhasil ditambahkan.');
    }

    public function show(User $tukang)
    {
        $tukang->load(['profile', 'hargaJasaTukangs', 'permintaanSebagaiTukang']);
        return view('admin.tukang.show', compact('tukang'));
    }

    public function edit(User $tukang)
    {
        $tukang->load('profile');
        return view('admin.tukang.edit', compact('tukang'));
    }

    public function update(Request $request, User $tukang)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $tukang->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'pengalaman' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'status_aktif' => $request->boolean('status_aktif', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $tukang->update($userData);

        $tukang->profile()->updateOrCreate(
            ['user_id' => $tukang->id],
            [
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pengalaman' => $request->pengalaman,
                'deskripsi' => $request->deskripsi,
            ]
        );

        return redirect()->route('admin.tukang.index')->with('success', 'Data tukang berhasil diperbarui.');
    }

    public function destroy(User $tukang)
    {
        $tukang->delete();
        return redirect()->route('admin.tukang.index')->with('success', 'Kepala tukang berhasil dihapus.');
    }
}
