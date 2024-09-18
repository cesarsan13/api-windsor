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
            $table->integer('recibo'); //(11)
            $table->date('fecha_cobro'); //(11)
            $table->time('hora'); //(13)
            $table->integer('alumno'); //(11)
            $table->decimal('importe_cobro', 8, 2); //double
            $table->integer('tipo_pago_1'); //(11)
            $table->decimal('importe_pago_1', 8, 2); //double
            $table->string('referencia_1')->nullable(); //(11)
            $table->integer('tipo_pago_2')->nullable(); //(11)
            $table->decimal('importe_pago_2', 8, 2)->nullable(); //double
            $table->string('referencia_2')->nullable(); //(11)
            $table->integer('cajero'); //(11)
            $table->integer('quien_paga')->nullable(); //(50)
            $table->text('comentario')->nullable(); //(11)
            $table->text('comentario_ad')->nullable(); //(50)
            $table->integer('cuen_banco')->nullable(); //(20)
            $table->string('referencia')->nullable(); //(10)
            $table->decimal('importe', 8, 2)->nullable(); //double

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
