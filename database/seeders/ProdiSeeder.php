<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Prodi::create(
            [
            'prodi'	=> 'TRPL',
            'jurusan_id' => 1
            ],
        );

        Prodi::create(
            [
            'prodi'	=> 'TRK',
            'jurusan_id' => 1
            ],

        );

        Prodi::create(
            [
            'prodi'	=> 'Pertanian',
            'jurusan_id' => 2
            ],

        );

        Prodi::create(
            [
            'prodi'	=> 'Perkebunan',
            'jurusan_id' => 2
            ],

        );
    }
}
