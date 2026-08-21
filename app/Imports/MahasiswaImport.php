<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil nomor identitas (NIP atau NIM) dari excel
        $idNumber = $row['nip'] ?? $row['nim'] ?? null;

        // Skip jika tidak ada nomor identitas
        if (!$idNumber) {
            return null;
        }

        // Check jika ada record yang sudah ada (termasuk yang di-soft delete)
        User::withTrashed()->where('nip', $idNumber)->orWhere('email', $row['email'] ?? null)->forceDelete();
        Mahasiswa::withTrashed()->where('nim', $idNumber)->orWhere('email', $row['email'] ?? null)->forceDelete();

        // Gunakan email dari excel atau buat email default
        $email = $row['email'] ?? ($idNumber . '@student.poliwangi.ac.id');

        // Buat akun User
        $user = User::create([
            'name' => $row['nama'] ?? 'Mahasiswa ' . $idNumber,
            'email' => $email,
            'password' => Hash::make($idNumber), // Password sama dengan NIP/NIM
            'nip' => $idNumber,
            'role_id' => 4, // Role Student
            'status' => 1,
        ]);

        return new Mahasiswa([
            'user_id' => $user->id,
            'nama'    => $row['nama'] ?? 'Mahasiswa ' . $idNumber,
            'nim'     => $idNumber,
            'email'   => $email,
            'whatsapp'=> $row['whatsapp'] ?? $row['no_hp'] ?? null,
            'jurusan' => $row['jurusan'] ?? $row['id_jurusan'] ?? $row['jurusan_id'] ?? null,
            'prodi'   => $row['prodi'] ?? $row['id_prodi'] ?? $row['prodi_id'] ?? null,
            'ormawa'  => $row['ormawa'] ?? $row['ormawa_id'] ?? null,
        ]);
    }
}