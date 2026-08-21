<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        // Mendapatkan semua data mahasiswa
        $alats = Alat::withTrashed()->get();

        // Mengembalikan data ke view 'alats.index'
        return view('alat.index', compact('alats'));
    }

    public function create()
    {
        $alat = Alat::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('alat.create', compact('alat'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'serial_number' => 'required',
            'stok' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $fotoName = null;
        $fileKey = $request->hasFile('foto') ? 'foto' : ($request->hasFile('foto_profil') ? 'foto_profil' : ($request->hasFile('foto_alat') ? 'foto_alat' : null));
        if ($fileKey) {
            $fileFoto = $request->file($fileKey);
            $fotoName = 'alat_' . time() . '_' . uniqid() . '.' . $fileFoto->getClientOriginalExtension();
            $fileFoto->move(public_path('uploads/alat'), $fotoName);
        }

        // Membuat mahasiswa baru
        Alat::create([
            'nama' => $request->nama,
            'serial_number' => $request->serial_number,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoName,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('alat')->with('success', 'alat berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        $alat = Alat::find($id);
        // Menampilkan form untuk menambahkan alat baru
        return view('alat.edit', compact('alat'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'serial_number' => 'required',
            'stok' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $alat = Alat::find($id);
        $alat->nama = $request->nama;
        $alat->serial_number = $request->serial_number;
        $alat->stok = $request->stok;
        $alat->deskripsi = $request->deskripsi;

        $fileKey = $request->hasFile('foto') ? 'foto' : ($request->hasFile('foto_profil') ? 'foto_profil' : ($request->hasFile('foto_alat') ? 'foto_alat' : null));
        if ($fileKey) {
            // Delete old photo if it exists
            if ($alat->foto && file_exists(public_path('uploads/alat/' . $alat->foto))) {
                @unlink(public_path('uploads/alat/' . $alat->foto));
            }

            $fileFoto = $request->file($fileKey);
            $fotoName = 'alat_' . time() . '_' . uniqid() . '.' . $fileFoto->getClientOriginalExtension();
            $fileFoto->move(public_path('uploads/alat'), $fotoName);
            $alat->foto = $fotoName;
        }

        $alat->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('alat')->with('success', 'alat berhasil diperbarui');
    }

    public function delete($id)
    {

        $alat = Alat::withTrashed()->where('id', $id)->first();
        if ($alat->deleted_at) {
            $alat->deleted_at = null;
            $alat->update();
        }
        else {
            $alat->delete();

        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('alat')->with('success', 'alat berhasil dihapus');
    }

    public function maintenance($id)
    {
        $alat = Alat::withTrashed()->where('id', $id)->first();
        if ($alat) {
            $alat->is_maintenance = !$alat->is_maintenance;
            $alat->update();
            $status = $alat->is_maintenance ? 'dimasukkan ke perbaikan' : 'dikeluarkan dari perbaikan';
            return redirect()->route('alat')->with('success', 'Alat berhasil ' . $status);
        }
        return redirect()->route('alat')->with('error', 'Alat tidak ditemukan');
    }
}
