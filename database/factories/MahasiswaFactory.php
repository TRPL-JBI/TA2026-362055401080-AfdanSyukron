<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Ormawa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama' => fake()->name(),
            'nim' => fake()->unique()->numerify('3622######'),
            'email' => fake()->unique()->safeEmail(),
            'whatsapp' => fake()->unique()->numerify('08##########'),
            'jurusan' => Jurusan::factory(),
            'prodi' => Prodi::factory(),
            'ormawa' => Ormawa::factory(),
            'foto_profil' => 'profil.png',
        ];
    }
}
