<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPengajuan extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [ "id" ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function detail_alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id')->withTrashed();
        // return $this->hasMany(Alat::class, 'id', 'alat_id')->withTrashed();
    }
}
