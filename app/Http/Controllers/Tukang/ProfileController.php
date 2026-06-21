<?php

namespace App\Http\Controllers\Tukang;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Http\Requests\Tukang\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('profile', 'hargaJasaTukangs');
        return view('tukang.profil.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('profile');
        return view('tukang.profil.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update(['name' => $request->name]);

        $profileData = [
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'pengalaman' => $request->pengalaman,
            'keahlian' => $request->keahlian,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($user->profile?->foto) {
                Storage::disk('public')->delete($user->profile->foto);
            }
            $path = $request->file('foto')->store('profiles', 'public');
            $profileData['foto'] = $path;
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('tukang.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
