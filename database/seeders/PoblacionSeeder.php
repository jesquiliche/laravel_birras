<?php

namespace Database\Seeders;

use App\Models\Poblacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\File;

class PoblacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('poblaciones')->delete();
        $json = File::get("database/seeders/data/poblaciones.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Poblacion::create(array(
                
                'codigo' => $obj->codigo,
                'provincia_cod'=>substr($obj->codigo,0,2),
                'nombre' => $obj->nombre,
            ));
         //   print "Insertando población -> ".$obj->codigo." ".$obj->nombre."\n";
        }

    }
}
