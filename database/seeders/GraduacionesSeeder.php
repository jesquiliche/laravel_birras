<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraduacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["nombre" => "Alta(7-9"],
            ["nombre" => "Baja(3-5)"],
            ["nombre" => "Maxima(12+)"],
            ["nombre" => "Muy alta(9-12"],
            ["nombre" => "Sin alcohol(0-2.9)"],
        ];
        
        DB::table('graduaciones')->insert($data);
    }
}
