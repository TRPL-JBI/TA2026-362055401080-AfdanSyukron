<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    //
    Public function index()
    {
        // Mendapatkan semua data mahasiswa
        $jurusans = Jurusan::all();

        // Mengembalikan data ke view 'jurusan.index'
        return view('jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('jurusan.create', compact('jurusan'));
    }

    public function edit(Request $request, $id)
    {
        $jurusan = Jurusan::find($id);
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('jurusan.edit', compact('jurusan'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Membuat mahasiswa baru
        Jurusan::create([
            'jurusan' => $request->nama,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jurusan')->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $jurusan = Jurusan::find($id);
        $jurusan->jurusan = $request->nama;
        $jurusan->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jurusan')->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function delete($id)
    {

        $jurusan = Jurusan::find($id);
        // Menghapus data 
        $jurusan->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('jurusan')->with('success', 'Mahasiswa berhasil dihapus');
    }
}
