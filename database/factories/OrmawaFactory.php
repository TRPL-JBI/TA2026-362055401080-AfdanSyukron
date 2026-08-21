<?php

namespace Database\Factories;

use App\Models\Ormawa;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrmawaFactory extends Factory
{
    protected $model = Ormawa::class;

    public function definition(): array
    {
        return [
            'ormawa' => fake()->randomElement(['BEM', 'HMTI', 'HIMAAGRI']),
        ];
    }
}
