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
        Schema::create('encab_pedidos', function (Blueprint $table) {
            $table->bigInteger('recibo')->primary()->default(0);
            $table->string('fecha', 15);
            $table->foreignId('alumno')->constrined('alumnos', 'id');
            $table->foreignId('cajero')->constrined('cajeros', 'numero');
            $table->decimal('importe_total', 8, 2);
            $table->integer('tipo_pago_1');
            $table->decimal('importe_pago_1', 8, 2);
            $table->string('referencia_1', 50)->nullable();
            $table->integer('tipo_pago_2')->nullable();
            $table->decimal('importe_pago_2', 8, 2)->nullable();
            $table->string('referencia_2', 50)->nullable();
            $table->string('nombre_quien', 100)->nullable();
            $table->text('comentario')->nullable();
            $table->string('comentario_ad', 100)->nullable();
            $table->string('facturado', 5)->nullable();
            $table->integer('numero_factura')->nullable();
            $table->string('fecha_factura', 15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encab__pedidos');
    }
};
