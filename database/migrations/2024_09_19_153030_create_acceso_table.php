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
        Schema::create('acceso', function (Blueprint $table) {
        $table->smallInteger('numero_prop'); 
        $table->string('clave_seguridad', 4);
        $table->string('clave_punto', 15);  
        $table->string('descripcion_punto', 35)->nullable(); 
        $table->tinyInteger('altas')->default(0);    
        $table->tinyInteger('bajas')->default(0);    
        $table->tinyInteger('cambios')->default(0);  
        $table->tinyInteger('impresion')->default(0);  
        $table->tinyInteger('cobros')->default(0); 
        $table->primary(['numero_prop', 'clave_seguridad', 'clave_punto']);
        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso');
    }
};
