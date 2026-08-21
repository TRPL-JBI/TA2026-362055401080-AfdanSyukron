<?php

namespace Database\Factories;

use App\Models\Alat;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlatFactory extends Factory
{
    protected $model = Alat::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['Kamera DSLR', 'Proyektor', 'Microphone Wireless', 'Sound System']),
            'serial_number' => fake()->unique()->numerify('SN-#####'),
            'stok' => (string) fake()->numberBetween(5, 20),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
