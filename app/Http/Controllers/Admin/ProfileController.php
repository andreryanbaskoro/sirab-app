<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('admin.profil.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('admin.profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = Auth::user();
        $user->update(['name' => $request->name]);

        $profileData = [
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ];

        if ($request->hasFile('foto')) {
            if ($user->profile?->foto) {
                Storage::disk('public')->delete($user->profile->foto);
            }
            $path = $request->file('foto')->store('profiles', 'public');
            $profileData['foto'] = $path;
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('admin.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
