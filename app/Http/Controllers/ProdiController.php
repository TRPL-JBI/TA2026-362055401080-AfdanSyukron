<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    //
    Public function index()
    {
        // Mendapatkan semua data mahasiswa
        $prodis = Prodi::all();

        // Mengembalikan data ke view 'prodi.index'
        return view('prodi.index', compact('prodis'));
    }

    public function create()
    {
        $prodi = Prodi::all();
        $jurusan = Jurusan::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('prodi.create', compact('prodi','jurusan'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'prodi' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
        ]);

        // Membuat mahasiswa baru
        prodi::create([
            'prodi' => $request->prodi,
            'jurusan_id' => $request->jurusan,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('prodi')->with('success', 'prodi berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        $prodi = Prodi::find($id);
        $jurusan = Jurusan::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('prodi.edit', compact('prodi', 'jurusan'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'jurusan' => 'required|string|max:100',
        ]);

        $prodi = Prodi::find($id);
        $prodi->prodi = $request->nama;
        $prodi->jurusan_id = $request->jurusan;
        $prodi->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('prodi')->with('success', 'prodi berhasil ditambahkan');
    }

    public function delete($id)
    {

        $prodi = Prodi::find($id);
        // Menghapus data 
        $prodi->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('prodi')->with('success', 'Mahasiswa berhasil dihapus');
    }
}
