<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->mahasiswa) {
            $user->mahasiswa->load('jurusan_mahasiswa', 'prodi_mahasiswa', 'ormawa_mahasiswa');
        }
        
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $isMahasiswa = ($user->mahasiswa) ? true : false;

        if ($isMahasiswa) {
            $mahasiswaId = $user->mahasiswa->id;
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id . '|unique:mahasiswas,email,' . $mahasiswaId,
                'whatsapp' => 'required|numeric|unique:mahasiswas,whatsapp,' . $mahasiswaId,
                'password' => 'nullable|string|min:6|confirmed',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5024',
            ]);

            // Update mahasiswa record
            $mahasiswa = $user->mahasiswa;
            $mahasiswa->nama = $request->name;
            $mahasiswa->email = $request->email;
            $mahasiswa->whatsapp = $request->whatsapp;

            if ($request->hasFile('foto_profil')) {
                // Delete old image if exists
                if ($mahasiswa->foto_profil && $mahasiswa->foto_profil !== 'default.png') {
                    Storage::delete('public/foto_profil/' . $mahasiswa->foto_profil);
                }

                $image = $request->file('foto_profil');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/foto_profil', $imageName);
                $mahasiswa->foto_profil = $imageName;
            }

            $mahasiswa->update();

        } else {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6|confirmed',
            ]);
        }

        // Update user record
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->update();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
