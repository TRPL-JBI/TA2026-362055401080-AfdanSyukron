<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    Public function index()
    {
        // Mendapatkan semua data mahasiswa
        $roles = Role::all();

        // Mengembalikan data ke view 'role.index'
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $role = Role::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('role.create', compact('role'));
    }

    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('role.edit', compact('role'));
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Membuat mahasiswa baru
        Role::create([
            'role' => $request->nama,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('role')->with('success', 'role berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $role = Role::find($id);
        $role->role = $request->nama;
        $role->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('role')->with('success', 'role berhasil ditambahkan');
    }

    public function delete($id)
    {

        $role = Role::find($id);
        // Menghapus data 
        $role->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('role')->with('success', 'Role berhasil dihapus');
    }
}
