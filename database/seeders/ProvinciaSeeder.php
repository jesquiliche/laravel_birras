<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return vocodigo
     */
    public function run()
    {
        //
        $provincias = array (
            array ('codigo' => "01", "nombre" => "ALAVA"),
            array ('codigo' => "02", "nombre" => "ALBACETE"),
            array ('codigo' => "03", "nombre" => "ALICANTE"),
            array ('codigo' => "04", "nombre" => "ALMERIA"),
            array ('codigo' => "33", "nombre" => "ASTURIAS"),
            array ('codigo' => "05", "nombre" => "AVILA"),
            array ('codigo' => "06", "nombre" => "BADAJOZ"),
            array ('codigo' => "08", "nombre" => "BARCELONA"),
            array ('codigo' => "09", "nombre" => "BURGOS"),
            array ('codigo' => "10", "nombre" => "CACERES"),
            array ('codigo' => "11", "nombre" => "CADIZ"),
            array ('codigo' => "39", "nombre" => "CANTABRIA"),
            array ('codigo' => "12", "nombre" => "CASTELLON"),
            array ('codigo' => "51", "nombre" => "CEUTA"),
            array ('codigo' => "13", "nombre" => "CIUDAD REAL"),
            array ('codigo' => "14", "nombre" => "CORDOBA"),
            array ('codigo' => "15", "nombre" => "CORUÑA"),
            array ('codigo' => "16", "nombre" => "CUENCA"),
            array ('codigo' => "17", "nombre" => "GIRONA"),
            array ('codigo' => "18", "nombre" => "GRANADA"),
            array ('codigo' => "19", "nombre" => "GUADALAJARA"),
            array ('codigo' => "20", "nombre" => "GUIPUZCOA"),
            array ('codigo' => "21", "nombre" => "HUELVA"),
            array ('codigo' => "22", "nombre" => "HUESCA"),
            array ('codigo' => "07", "nombre" => "ILLES BALEARS"),
            array ('codigo' => "23", "nombre" => "JAEN"),
            array ('codigo' => "24", "nombre" => "LEON"),
            array ('codigo' => "25", "nombre" => "LLEcodigoA"),
            array ('codigo' => "27", "nombre" => "LUGO"),
            array ('codigo' => "28", "nombre" => "MADRID"),
            array ('codigo' => "29", "nombre" => "MALAGA"),
            array ('codigo' => "52", "nombre" => "MELILLA"),
            array ('codigo' => "30", "nombre" => "MURCIA"),
            array ('codigo' => "31", "nombre" => "NAVARRA"),
            array ('codigo' => "32", "nombre" => "OURENSE"),
            array ('codigo' => "34", "nombre" => "PALENCIA"),
            array ('codigo' => "35", "nombre" => "PALMAS, LAS"),
            array ('codigo' => "36", "nombre" => "PONTEVEDRA"),
            array ('codigo' => "26", "nombre" => "RIOJA, LA"),
            array ('codigo' => "37", "nombre" => "SALAMANCA"),
            array ('codigo' => "38", "nombre" => "SANTA CRUZ DE TENERIFE"),
            array ('codigo' => "40", "nombre" => "SEGOVIA"),
            array ('codigo' => "41", "nombre" => "SEVILLA"),
            array ('codigo' => "42", "nombre" => "SORIA"),
            array ('codigo' => "43", "nombre" => "TARRAGONA"),
            array ('codigo' => "44", "nombre" => "TERUEL"),
            array ('codigo' => "45", "nombre" => "TOLEDO"),
            array ('codigo' => "46", "nombre" => "VALENCIA"),
            array ('codigo' => "47", "nombre" => "VALLADOLID"),
            array ('codigo' => "48", "nombre" => "VIZCAYA"),
            array ('codigo' => "49", "nombre" => "ZAMORA"),
            array ('codigo' => "50", "nombre" => "ZARAGOZA")
       );
       print "Insertando provincias\n";
       DB::table('provincias')->insert($provincias);
    }
}
