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
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->integer('recibo')->primary()->default(0); //(11)
            $table->integer('alumno')->primary()->default(0); //(11)
            $table->integer('articulo')->primary()->default(0); //(20)
            $table->integer('documento')->primary()->default(0); //(11)
            $table->string('fecha',11);
            $table->integer('cantidad'); //(11)
            $table->float('precio_unitario');
            $table->float('descuento');
            $table->float('iva');
            $table->integer('numero_factura');
            $table->foreign('alumno')->references('id')->on('alumnos');
            $table->foreign('articulo')->references('id')->on('productos');
            $table->foreign('alumno')->references('id')->on('alumnos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
