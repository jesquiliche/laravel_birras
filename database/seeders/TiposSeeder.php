<?php

namespace Database\Seeders;

use App\Models\Tipo;
use \Illuminate\Support\Facades\File;
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
        DB::table('tipos')->delete();
        $json = File::get("database/seeders/data/tipos.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Tipo::create(array(
                'nombre' => $obj->nombre,
                'descripcion' => $obj->descripcion,
            ));
            //  print "Insertando cerveza -> ".$obj->nombre."\n";
        }
    }
}
