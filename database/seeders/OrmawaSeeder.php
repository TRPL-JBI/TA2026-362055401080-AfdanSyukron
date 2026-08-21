<?php

namespace Database\Seeders;

use App\Models\Ormawa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrmawaSeeder extends Seeder
{
    public function run(): void
    {
        //
        Ormawa::create(
            [
            'ormawa'	=> 'HMTI',
            ],
        );

        Ormawa::create(
            [
            'ormawa'	=> 'HMTM',
            ],

        );

        Ormawa::create(
            [
            'ormawa'	=> 'HIMAGRI',
            ],

        );

        Ormawa::create(
            [
            'ormawa'	=> 'HMS',
            ],

        );
    }
}
