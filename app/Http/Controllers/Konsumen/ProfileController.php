<?php

namespace App\Http\Controllers\Konsumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Konsumen\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('konsumen.profil.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('konsumen.profil.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
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

        return redirect()->route('konsumen.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
