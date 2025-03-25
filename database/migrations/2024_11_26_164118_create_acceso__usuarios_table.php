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
        if (!Schema::connection('dynamic')->hasTable('acceso_usuarios')) {
        Schema::create('acceso_usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_punto_menu');
            $table->boolean('t_a')->default(false)->nullable(false);
            $table->boolean('altas')->default(false)->nullable(false);
            $table->boolean('bajas')->default(false)->nullable(false);
            $table->boolean('cambios')->default(false)->nullable(false);
            $table->boolean('impresion')->default(false)->nullable(false);
            $table->primary(['id_usuario','id_punto_menu']);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acceso_usuarios');
    }
};
