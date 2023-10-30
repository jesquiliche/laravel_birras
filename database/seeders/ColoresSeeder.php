<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ["nombre" => "Amarillo"],
            ["nombre" => "Ambar"],
            ["nombre" => "Blanca"],
            ["nombre" => "Cobrizo"],
            ["nombre" => "Marrón"],
            ["nombre" => "Negra"],
            ["nombre" => "Rubia"],
            ["nombre" => "Tostada"]
        ];

        DB::table('colores')->insert($data);
    }
}
