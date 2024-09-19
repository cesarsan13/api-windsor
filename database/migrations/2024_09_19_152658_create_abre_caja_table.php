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
        Schema::create('abre_caja', function (Blueprint $table) {
        $table->double('caja');
        $table->double('cajero');
        $table->string('fecha', 11);
        $table->string('hora', 11);
        $table->double('fondo')->nullable();
        $table->double('conteo')->nullable();
        $table->tinyInteger('abierto')->default(0);
        $table->string('clave', 8)->nullable();
        $table->string('hora_cierre', 11)->nullable();
        $table->primary(['caja', 'cajero', 'fecha', 'hora']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abre_caja');
    }
};
