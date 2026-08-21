<?php

namespace App\Http\Controllers;

use App\Models\Ormawa;
use Illuminate\Http\Request;

class OrmawaController extends Controller
{
    //
    Public function index()
    {
        // Mendapatkan semua data mahasiswa
        $ormawas = Ormawa::all();

        // Mengembalikan data ke view 'ormawa.index'
        return view('ormawa.index', compact('ormawas'));
    }

    public function create()
    {
        $ormawa = Ormawa::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('ormawa.create', compact('ormawa'));
    }

    public function edit(Request $request, $id)
    {
        $ormawa = Ormawa::find($id);
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('ormawa.edit', compact('ormawa'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $ormawa = Ormawa::find($id);
        $ormawa->ormawa = $request->nama;
        $ormawa->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('ormawa')->with('success', 'ormawa berhasil ditambahkan');
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Membuat mahasiswa baru
        Ormawa::create([
            'ormawa' => $request->nama,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('ormawa')->with('success', 'ormawa berhasil ditambahkan');
    }
    
    public function delete($id)
    {

        $ormawa = Ormawa::find($id);
        // Menghapus data 
        $ormawa->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('ormawa')->with('success', 'Mahasiswa berhasil dihapus');
    }
}

