<?php

namespace Database\Seeders;

use App\Models\Cerveza;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\File;

class CervezasSeeder extends Seeder
{
    public function run()
    {
        DB::table('cervezas')->delete();
        $json = File::get("database/seeders/data/cervezas.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            Cerveza::create(array(
                'nombre'=>$obj->nombre,
                'descripcion' => $obj->descripcion,
                'color_id'=>$obj->color_id,
                'tipo_id' => $obj->tipo_id,
                'pais_id' =>$obj->pais_id,
                'graduacion_id'=>$obj->graduacion_id,
                'marca'=>$obj->marca,
                'precio'=>$obj->precio,
                'foto'=>$obj->foto
            ));
            print "Insertando cerveza -> ".$obj->nombre."\n";
        }

    }
}
