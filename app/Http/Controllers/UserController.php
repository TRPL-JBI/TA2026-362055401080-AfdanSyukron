<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    Public function index()
    {
        // Mendapatkan semua data mahasiswa
        $users = User::all();

        // Mengembalikan data ke view 'user.index'
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $user = User::all();
        $role = Role::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('user.create', compact('user','role'));
    }

    public function store(Request $request)
    {
        // Check for existing users (including trashed) with same email or NIP
        User::withTrashed()
            ->where('email', $request->email)
            ->orWhere('nip', $request->nip)
            ->forceDelete();

        // Check for existing mahasiswa (including trashed) with same email or NIP
        Mahasiswa::withTrashed()
            ->where('email', $request->email)
            ->orWhere('nim', $request->nip)
            ->forceDelete();

        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:100|unique:users,nip',
            'email' => 'required|unique:users,email|string|max:100',
            'password' => 'required|string|max:100',
            'role' => 'required|string|max:100',
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $request->nama,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => $request->role,
            'status' => 1,
        ]);

        if ($request->role == 4) {
            // Membuat mahasiswa baru
            Mahasiswa::create([
                'nama' => $request->nama,
                'nim' => $request->nip,
                'email' => $request->email,
                'user_id' => $user->id,
            ]);
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user')->with('success', 'user berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        $role = Role::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('user.edit', compact('user', 'role'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'nip' => 'required|string|max:100',
            'email' => 'required|string|max:100',
            'password' => 'required|string|max:100',
            'role' => 'required|string|max:100',
        ]);

        $user = User::find($id);
        $user->name = $request->nama;
        $user->nip = $request->nip;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role_id = $request->role;
        $user->update();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user')->with('success', 'user berhasil ditambahkan');
    }

    public function delete($id)
    {

        $user = User::find($id);
        // Menghapus data 
        $user->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('user')->with('success', 'Mahasiswa berhasil dihapus');
    }
}
