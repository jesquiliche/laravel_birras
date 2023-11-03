<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cervezas', function (Blueprint $table) {
            $table->boolean('novedad')->default(false);
            $table->boolean('oferta')->default(false);
            $table->decimal('precio', 8, 2)->default(0); // 8 dígitos en total y 2 decimales
            $table->string('foto')->default('');
            $table->string('marca', 150)->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
