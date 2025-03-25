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
        if (!Schema::connection('dynamic')->hasTable('punto_menu')) {
        Schema::create('punto_menu', function (Blueprint $table) {
            $table->string('clave_punto', 15);   
            $table->string('descripcion_punto', 35)->nullable();  
            $table->primary('clave_punto');
            $table->timestamps();
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punto_menu');
    }
};
