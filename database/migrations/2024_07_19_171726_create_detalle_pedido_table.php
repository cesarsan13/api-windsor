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
        if (!Schema::connection('dynamic')->hasTable('detalle_pedido')) {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->integer('recibo')->default(0); //(11)
            $table->integer('alumno')->default(0); //(11)
            $table->integer('articulo')->default(0); //(20)
            $table->integer('documento')->default(0); //(11)
            $table->string('fecha',11);
            $table->integer('cantidad'); //(11)
            $table->float('precio_unitario');
            $table->float('descuento');
            $table->float('iva');
            $table->integer('numero_factura');
            $table->primary(['recibo', 'alumno', 'articulo','documento']);
            // $table->foreign('alumno')->references('numero')->on('alumnos');
            // $table->foreign('articulo')->references('numero')->on('productos');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
