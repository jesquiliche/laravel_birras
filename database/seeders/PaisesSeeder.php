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
            ["nombre" => "Bélgica"],
            ["nombre" => "EEUU"],
            ["nombre" =>"Escocia"],
            ["nombre" => "Holanda"],
            ["nombre" => "Inglaterra"],
            ["nombre" => "Irlanda"],
            ["nombre" => "Austria"],
            ["nombre" => "Nueva Zelanda"],
        ];

        DB::table('paises')->insert($data);
    }
}
