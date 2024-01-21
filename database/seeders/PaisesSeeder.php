<?php

namespace Database\Seeders;

use App\Models\Pais;
use \Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paises')->delete();
        $json = File::get("database/seeders/data/paises.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Pais::create(array(
                'nombre' => $obj->nombre,
                'descripcion' => $obj->descripcion,
            ));
            //  print "Insertando cerveza -> ".$obj->nombre."\n";
        }
    }
    
}
