<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KonsultanController extends Controller
{
    public function index()
    {
        $data = User::role('konsultan')->with('profile')->latest()->paginate(10);
        return view('admin.konsultan.index', compact('data'));
    }

    public function create()
    {
        return view('admin.konsultan.create');
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
            'role' => 'konsultan',
            'status_aktif' => true,
        ]);
        
        $user->assignRole('konsultan');

        Profile::create([
            'user_id' => $user->id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'pengalaman' => $request->pengalaman,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('admin.konsultan.index')->with('success', 'Konsultan berhasil ditambahkan.');
    }

    public function show(User $konsultan)
    {
        $konsultan->load(['profile', 'hargaJasaKonsultans', 'permintaanSebagaiKonsultan']);
        return view('admin.konsultan.show', compact('konsultan'));
    }

    public function edit(User $konsultan)
    {
        $konsultan->load('profile');
        return view('admin.konsultan.edit', compact('konsultan'));
    }

    public function update(Request $request, User $konsultan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $konsultan->id,
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

        $konsultan->update($userData);

        $konsultan->profile()->updateOrCreate(
            ['user_id' => $konsultan->id],
            [
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pengalaman' => $request->pengalaman,
                'deskripsi' => $request->deskripsi,
            ]
        );

        return redirect()->route('admin.konsultan.index')->with('success', 'Data konsultan berhasil diperbarui.');
    }

    public function destroy(User $konsultan)
    {
        $konsultan->delete();
        return redirect()->route('admin.konsultan.index')->with('success', 'Konsultan berhasil dihapus.');
    }
}
