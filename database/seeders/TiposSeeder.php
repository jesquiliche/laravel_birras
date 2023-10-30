<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["nombre" => "Ale"],
            ["nombre" => "Lager/Pilsner"],
            ["nombre" => "Stout"],
            ["nombre" => "Porter"],
            ["nombre" => "IPA (India Pale Ale)"],
            ["nombre" => "Wheat Beer"],
            ["nombre" => "Sour Beer"],
            ["nombre" => "Belgian Ale"],
            ["nombre" => "Pale Ale"],
            ["nombre" => "Brown Ale"],
            ["nombre" => "Amber Ale"],
            ["nombre" => "Golden Ale"],
            ["nombre" => "Blonde Ale"],
            ["nombre" => "Cream Ale"],
            ["nombre" => "Kölsch"],
            ["nombre" => "Pilsner"],
            ["nombre" => "Bock"],
            ["nombre" => "Doppelbock"],
            ["nombre" => "Helles"],
            ["nombre" => "Vienna Lager"],
            ["nombre" => "Marzen/Oktoberfest"],
            ["nombre" => "Kellerbier"],
            ["nombre" => "Dunkel"],
            ["nombre" => "Schwarzbier"],
            ["nombre" => "Barleywine"],
            ["nombre" => "Saison"],
            ["nombre" => "Witbier"],
            ["nombre" => "Gose"],
            ["nombre" => "Kvass"],
            ["nombre" => "Rauchbier"],
            ["nombre" => "Fruit Beer"],
            ["nombre" => "Cider"],
            ["nombre" => "Mead"],
        ];
        
        DB::table('tipos')->insert($data);
    }
}
