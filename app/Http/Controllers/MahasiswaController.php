<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use App\Models\Ormawa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan semua data mahasiswa
        $mahasiswas = Mahasiswa::all();

        // Mengembalikan data ke view 'mahasiswas.index'
        return view('mahasiswa.index', compact('mahasiswas'));
    
        $mahasiswa = Mahasiswa::latest()->get();
        return view('mahasiswa.index', compact('mahasiswa'));
    
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        $ormawa = Ormawa::all();
        // Menampilkan form untuk menambahkan mahasiswa baru
        return view('mahasiswa.create', compact('jurusan','prodi','ormawa'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check for existing users (including trashed) with same email or NIP
        User::withTrashed()
            ->where('email', $request->email)
            ->orWhere('nip', $request->nim)
            ->forceDelete();

        // Check for existing mahasiswa (including trashed) with same email, NIM, or WhatsApp
        Mahasiswa::withTrashed()
            ->where('email', $request->email)
            ->orWhere('nim', $request->nim)
            ->orWhere('whatsapp', $request->whatsapp)
            ->forceDelete();

        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'email' => 'required|email|unique:mahasiswas,email',
            'whatsapp' => 'required|numeric|unique:mahasiswas,whatsapp',
            'jurusan' => 'required',
            'prodi' => 'required',
            'ormawa' => 'required',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:5024', // Validasi gambar
        ]);

        // Proses upload foto profil
        if ($request->hasFile('foto_profil')) {
            $image = $request->file('foto_profil');
            $imageName = time() . '.' . $image->getClientOriginalExtension();  // Nama file unik
            $imagePath = $image->storeAs('public/foto_profil', $imageName);    // Simpan di folder storage
            // $validated['foto_profil'] = $imageName;   // Not used in this context but keeping logic flow
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->nim),
            'nip' => $request->nim,
            'role_id' => 4,
            'status' => 1,
        ]);

        // Membuat mahasiswa baru
        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'jurusan' => $request->jurusan,
            'prodi' => $request->prodi,
            'foto_profil' => $imageName ?? 'default.png',
            'user_id' => $user->id,
            'ormawa' => $request->ormawa,
        ]);

        // Simpan data mahasiswa ke database
        // Mahasiswa::create($validated);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        // Menampilkan detail dari mahasiswa
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $currentUser = auth()->user();
        $userRole = strtolower($currentUser->role->role ?? '');

        if ($userRole === 'mahasiswa') {
            if (!$currentUser->mahasiswa || $currentUser->mahasiswa->id != $id) {
                abort(403, 'Akses Ditolak: Anda hanya dapat mengedit profil Anda sendiri.');
            }
        }

        $mahasiswa = Mahasiswa::findOrFail($id);
        $jurusan = Jurusan::all();
        $prodi = Prodi::all();
        $ormawa = Ormawa::all();
        // Menampilkan form edit mahasiswa
        return view('mahasiswa.edit', compact('mahasiswa', 'jurusan','prodi','ormawa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        $userRole = strtolower($currentUser->role->role ?? '');

        if ($userRole === 'mahasiswa') {
            if (!$currentUser->mahasiswa || $currentUser->mahasiswa->id != $id) {
                abort(403, 'Akses Ditolak: Anda hanya dapat memperbarui profil Anda sendiri.');
            }
        }

        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string',
            'email' => 'required|email',
            'whatsapp' => 'required|numeric',
            'jurusan' => 'required',
            'prodi' => 'required',
            'ormawa' => 'required',
            // 'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        // Mengupdate data mahasiswa
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->nama = $request->nama;
        $mahasiswa->nim = $request->nim;
        $mahasiswa->email = $request->email;
        $mahasiswa->whatsapp = $request->whatsapp;
        $mahasiswa->jurusan = $request->jurusan;
        $mahasiswa->prodi = $request->prodi;
        $mahasiswa->ormawa = $request->ormawa;
        // $mahasiswa->foto_profil = $request->foto_profil;
        $mahasiswa->update();

        // Mengupdate data user
        $user = User::find($mahasiswa->user_id);
        if ($user) {
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->update();
        }

        // Redirect sesuai role
        if ($userRole === 'mahasiswa') {
            return redirect()->route('pengajuan')->with('success', 'Data mahasiswa berhasil diupdate');
        }

        return redirect()->route('mahasiswa')->with('success', 'Data mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $mahasiswa = Mahasiswa::find($id);
        // Menghapus data 
        $mahasiswa->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048'
        ]);

        Excel::import(new MahasiswaImport, $request->file('file'));

        return redirect()->route('mahasiswa')
                         ->with('success', 'Data mahasiswa berhasil diimport!');
    }
}