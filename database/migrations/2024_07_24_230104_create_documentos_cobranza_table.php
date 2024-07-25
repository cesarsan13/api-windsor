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
        Schema::create('documentos_cobranza', function (Blueprint $table) {
            $table->integer("alumno");
            $table->integer('producto');
            $table->integer('numero_doc');
            $table->string('fecha',11);
            $table->double('descuento');
            $table->double('importe');
            $table->string('fecha_cobro');
            $table->double('importe_pago');
            $table->string('ref',3);
            $table->string('grupo');
            $table->integer('orden');
            $table->string('baja',1);

            $table->primary(['alumno', 'producto', 'numero_doc','fecha']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_cobranza');
    }
};
