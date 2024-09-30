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
        Schema::create('socio_paquete', function (Blueprint $table) {
            $table->integer('socio')->unsigned(); 
            $table->string('paquete', 6);              
            $table->string('fecha_inicio', 50);      
            $table->string('fecha_fin', 11)->nullable(); 
            $table->char('baja',1)->default('')->nullable(false);
            $table->primary(['socio', 'paquete', 'fecha_inicio']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socio_paquete');
    }
};
