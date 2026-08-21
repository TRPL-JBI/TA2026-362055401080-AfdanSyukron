<?php

namespace Database\Factories;

use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdiFactory extends Factory
{
    protected $model = Prodi::class;

    public function definition(): array
    {
        return [
            'prodi' => fake()->randomElement(['D3 Teknik Informatika', 'D4 Teknologi Rekayasa Perangkat Lunak', 'D3 Agribisnis']),
            'jurusan_id' => Jurusan::factory(),
        ];
    }
}
