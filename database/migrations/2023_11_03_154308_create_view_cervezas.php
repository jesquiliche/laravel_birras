<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ejecuta la sentencia SQL para crear o reemplazar la vista
        DB::statement('
            CREATE OR REPLACE VIEW v_cervezas AS
            SELECT 
                cer.id AS id,
                cer.nombre AS nombre,
                cer.descripcion AS descripcion,
                cer.novedad AS novedad,
                cer.oferta AS oferta,
                cer.precio AS precio,
                cer.foto AS foto,
                cer.marca AS marca,
                col.nombre AS color,
                g.nombre AS graduacion,
                t.nombre AS tipo,
                p.nombre AS pais
            FROM
                cervezas cer
                JOIN colores col ON (cer.color_id = col.id)
                JOIN graduaciones g ON (cer.graduacion_id = g.id)
                JOIN tipos t ON (t.id = cer.tipo_id)
                JOIN paises p ON (p.id = cer.pais_id)
            ORDER BY nombre
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Si deseas eliminar la vista en una migración de reversión, puedes hacerlo así:
        DB::statement('DROP VIEW IF EXISTS v_cervezas');
    }
};
