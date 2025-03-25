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
        if (!Schema::connection('dynamic')->hasTable('trab_rep_cobr')) {
        Schema::create('trab_rep_cobr', function (Blueprint $table) {
            $table->double('recibo');     
            $table->string('fecha', 11);     
            $table->string('articulo', 20);  
            $table->string('documento', 10);   
            $table->double('alumno');     
            $table->string('nombre', 50)->nullable();
            $table->double('importe')->nullable();
            $table->primary(['recibo', 'fecha', 'articulo', 'documento', 'alumno']);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trab_rep_cobr');
    }
};
