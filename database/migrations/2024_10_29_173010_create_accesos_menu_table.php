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
        Schema::create('accesos_menu', function (Blueprint $table) {
            $table->unsignedBigInteger('numero')->primary();
            $table->string('ruta')->default('');
            $table->string('descripcion', 100)->default('');
            $table->string('icono', 100)->default('');
            $table->string('menu', 100)->default('');
            $table->string('baja', 1)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesos_menu');
    }
};
