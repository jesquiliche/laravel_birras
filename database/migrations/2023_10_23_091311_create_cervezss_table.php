<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cervezas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',150);
            $table->text('descripcion');
            $table->unsignedBigInteger('color_id');
            $table->foreign('color_id')->references('id')->on('colores');
            $table->unsignedBigInteger('graduacion_id');
            $table->foreign('graduacion_id')->references('id')->on('graduaciones');
            $table->unsignedBigInteger('tipo_id');
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->unsignedBigInteger('pais_id');
            $table->foreign('pais_id')->references('id')->on('paises');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cervezas');
    }
};
