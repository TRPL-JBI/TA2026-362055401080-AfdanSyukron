<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    // Definisikan tabel yang akan digunakan oleh model ini
    // protected $table = 'mahasiswas';

    protected $guarded = [ "id" ];

    // Tentukan kolom-kolom yang bisa diisi secara massal
    // protected $fillable = [
    //     'user_id',
    //     'nama',
    //     'nim',
    //     'email',
    //     'whatsapp',
    //     'jurusan_id',
    //     'prodi_id',
    //     'ormawa_id',
    //     'foto_profil',  // Menambahkan foto_profil ke $fillable
    // ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke tabel Jurusan
    
    public function jurusan_mahasiswa()
    {
        return $this->hasOne(Jurusan::class, 'id', 'jurusan');
    }

    // Relasi ke tabel Prodi
    public function prodi_mahasiswa()
    {
        return $this->hasOne(Prodi::class, 'id', 'prodi');
    }

    // Relasi ke tabel Ormawa
    public function ormawa_mahasiswa()
    {
        return $this->hasOne(Ormawa::class, 'id', 'ormawa');
    }

    // public function jurusan()
    // {
    //     return $this->hasOne(Jurusan::class, 'jurusan', 'jurusan');
    // }

    // public function prodi()
    // {
    //     return $this->hasOne(Prodi::class, 'prodi', 'prodi');
    // }

    // public function ormawa()
    // {
    //     return $this->hasOne(Ormawa::class, 'ormawa', 'ormawa');
    // }
}
