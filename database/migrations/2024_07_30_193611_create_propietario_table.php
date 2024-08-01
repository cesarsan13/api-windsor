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
        Schema::create('propietario', function (Blueprint $table) {            
            $table->bigInteger('numero')->primary()->default(0);
            $table->string('nombre', 50);
            $table->string('clave_seguridad', 10);
            $table->integer('busqueda_max')->unsigned();
            $table->double('inscripcion', 8, 2);
            $table->integer('con_recibos')->unsigned();
            $table->integer('con_facturas')->unsigned();
            $table->string('clave_bonificacion', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propietario');
    }
};
