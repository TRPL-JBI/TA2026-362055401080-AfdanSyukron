<?php

namespace Database\Factories;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengajuanFactory extends Factory
{
    protected $model = Pengajuan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama_kegiatan' => fake()->sentence(3),
            'tanggal_peminjaman' => now()->addDays(1)->format('Y-m-d'),
            'tanggal_pengembalian' => now()->addDays(3)->format('Y-m-d'),
            'status' => 'pending',
            'file' => 'test_file.pdf',
            'ktm' => 'test_ktm.png',
        ];
    }
}
