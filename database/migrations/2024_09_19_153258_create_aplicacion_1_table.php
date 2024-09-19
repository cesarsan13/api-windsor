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
        Schema::create('aplicacion_1', function (Blueprint $table) {
            $table->bigInteger('numero')-> primary()->default(0);
            $table->string('numero_cuenta', 35);   
            $table->string('cargo_abono', 1);      
            $table->double('importe_movimiento'); 
            $table->string('referencia', 11)->nullable();  
            $table->string('fecha_referencia', 11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicacion_1');
    }
};
