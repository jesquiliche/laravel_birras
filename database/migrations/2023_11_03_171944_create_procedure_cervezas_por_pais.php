<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('
        CREATE PROCEDURE IF NOT EXISTS GetCervezasByPais(IN paisId INT)
        BEGIN
            SELECT * FROM cervezas WHERE pais_id = paisId;
        END;
    ');
    }
    
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS GetCervezasByPais');
    }
};
