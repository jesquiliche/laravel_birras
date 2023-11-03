<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggerCervezas extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Crear la tabla cervezas_copia
        DB::unprepared('
        CREATE TABLE cervezas_copia AS
        SELECT *, "INSERT" AS operacion, NOW() AS fecha_operacion
        FROM cervezas WHERE 1=0;
    ');
    


        // Crear el trigger
        DB::unprepared('
            CREATE TRIGGER copiar_cervezas_after_update
            AFTER UPDATE ON cervezas FOR EACH ROW
            BEGIN
                INSERT INTO cervezas_copia (id, nombre, descripcion, color_id, graduacion_id, tipo_id, pais_id, created_at, updated_at, operacion,fecha_operacion)
                SELECT OLD.id, NEW.nombre, OLD.descripcion, OLD.color_id, OLD.graduacion_id, OLD.tipo_id, OLD.pais_id, OLD.created_at, OLD.updated_at, "UPDATE",NOW();
            END;
        ');
    

        DB::unprepared('
            CREATE TRIGGER copiar_cervezas_before_delete
            BEFORE DELETE ON cervezas FOR EACH ROW
            BEGIN
                INSERT INTO cervezas_copia (id, nombre, descripcion, color_id, graduacion_id, tipo_id, pais_id, created_at, updated_at, operacion,fecha_operacion)
                SELECT OLD.id, OLD.nombre, OLD.descripcion, OLD.color_id, OLD.graduacion_id, OLD.tipo_id, OLD.pais_id, OLD.created_at, NOW(), "DELETE",NOW();
            END;
        ');
    
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Eliminar los triggers
        DB::unprepared('DROP TRIGGER IF EXISTS copiar_cervezas_after_update');

        // Eliminar los triggers
        DB::unprepared('DROP TRIGGER IF EXISTS copiar_cervezas_before_delete');


        // Eliminar la tabla cervezas_copia
        DB::unprepared('DROP TABLE IF EXISTS cervezas_copia');
    }
}
