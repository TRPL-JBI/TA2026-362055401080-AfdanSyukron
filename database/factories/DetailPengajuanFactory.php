<?php

namespace Database\Factories;

use App\Models\DetailPengajuan;
use App\Models\Pengajuan;
use App\Models\Alat;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetailPengajuanFactory extends Factory
{
    protected $model = DetailPengajuan::class;

    public function definition(): array
    {
        return [
            'pengajuan_id' => Pengajuan::factory(),
            'alat_id' => Alat::factory(),
            'qty' => (string) fake()->numberBetween(1, 3),
        ];
    }
}
