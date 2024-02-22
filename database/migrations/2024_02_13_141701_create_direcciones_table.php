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
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('apellidos',150);
            $table->string('calle',150);
            $table->string('numero',5);
            $table->string('escalera',5)->nullable();
            $table->string('piso',20)->nullable();
            $table->string('puerta',5)->nullable();
            $table->string('poblacion',5);
            $table->string('provincia',2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('telefono',15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direccions');
    }
};
