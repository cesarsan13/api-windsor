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
        Schema::create('trab_rep_cobr', function (Blueprint $table) {
            $table->integer('recibo');
            $table->string('fecha',11);
            $table->integer('articulo');
            $table->integer('documento');
            $table->integer('alumno');
            $table->string('nombre');
            $table->double('importe');
            $table->primary(['recibo', 'fecha', 'articulo','documento','alumno']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trab_rep_cobr');
    }
};
