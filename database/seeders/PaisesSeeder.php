<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["nombre" => "España"],
            ["nombre" => "Alemania"],
            ["nombre" => "Francia"],
            ["nombre" => "República checa"],
            ["nombre" => "Belgica"],
            ["nombre" => "EEUU"],
            ["nombre" =>"Escocia"],
            ["nombre" => "Holanda"],
            ["nombre" => "Inglaterra"],
            ["nombre" => "Irlanda"],
        ];

        DB::table('Paises')->insert($data);
    }
}
