<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KonsumenController extends Controller
{
    public function index()
    {
        $data = User::role('konsumen')->with('profile')->latest()->paginate(10);
        return view('admin.konsumen.index', compact('data'));
    }

    public function create()
    {
        return view('admin.konsumen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'konsumen',
            'status_aktif' => true,
        ]);
        
        $user->assignRole('konsumen');

        Profile::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.konsumen.index')->with('success', 'Konsumen berhasil ditambahkan.');
    }

    public function show(User $konsumen)
    {
        $konsumen->load(['profile', 'permintaanSebagaiKonsumen.tukang', 'permintaanSebagaiKonsumen.rab']);
        return view('admin.konsumen.show', compact('konsumen'));
    }

    public function edit(User $konsumen)
    {
        $konsumen->load('profile');
        return view('admin.konsumen.edit', compact('konsumen'));
    }

    public function update(Request $request, User $konsumen)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $konsumen->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'status_aktif' => $request->boolean('status_aktif', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $konsumen->update($userData);

        $konsumen->profile()->updateOrCreate(
            ['user_id' => $konsumen->id],
            [
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]
        );

        return redirect()->route('admin.konsumen.index')->with('success', 'Data konsumen berhasil diperbarui.');
    }

    public function destroy(User $konsumen)
    {
        $konsumen->delete();
        return redirect()->route('admin.konsumen.index')->with('success', 'Konsumen berhasil dihapus.');
    }
}
