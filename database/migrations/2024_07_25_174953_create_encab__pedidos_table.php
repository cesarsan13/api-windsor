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
        Schema::create('encab_pedidos', function (Blueprint $table) { //encab_pedido
            $table->bigInteger('recibo')->primary()->default(0); //(11)
            $table->string('fecha', 15);
            $table->foreignId('alumno')->constrined('alumnos', 'id'); //(11)
            $table->foreignId('cajero')->constrined('cajeros', 'numero'); //(11)
            $table->decimal('importe_total', 8, 2); //double
            $table->integer('tipo_pago_1'); //(11)
            $table->decimal('importe_pago_1', 8, 2); //double
            $table->string('referencia_1', 50)->nullable(); //(11)
            $table->integer('tipo_pago_2')->nullable(); //(11)
            $table->decimal('importe_pago_2', 8, 2)->nullable(); //double
            $table->string('referencia_2', 50)->nullable(); //(11)
            $table->string('nombre_quien', 100)->nullable();//(50)
            $table->text('comentario')->nullable(); //(11)
            $table->string('comentario_ad', 100)->nullable(); //(50)
            $table->string('facturado', 5)->nullable(); //(1)
            $table->integer('numero_factura')->nullable(); //(11)
            $table->string('fecha_factura', 15)->nullable(); //(11)
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
