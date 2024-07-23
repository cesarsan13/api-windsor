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
        Schema::create('cobranza_diaria', function (Blueprint $table) {
            $table->integer('recibo');
            $table->date('fecha_cobro');
            $table->time('hora');
            $table->integer('alumno');
            $table->decimal('importe_cobro', 8, 2);
            $table->integer('tipo_pago_1');
            $table->decimal('importe_pago_1', 8, 2);
            $table->string('referencia_1')->nullable();
            $table->integer('tipo_pago_2')->nullable();
            $table->decimal('importe_pago_2', 8, 2)->nullable();
            $table->string('referencia_2')->nullable();
            $table->integer('cajero');
            $table->integer('quien_paga')->nullable();
            $table->text('comentario')->nullable();
            $table->text('comentario_ad')->nullable();
            $table->integer('cuen_banco')->nullable();
            $table->string('referencia')->nullable();
            $table->decimal('importe', 8, 2)->nullable();

            $table->primary(['recibo', 'fecha_cobro', 'hora']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranza_diaria');
    }
};
