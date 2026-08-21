<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengajuan extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [ "id" ];

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'user_id', 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPengajuan::class, 'pengajuan_id');
    }

}
