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
        Schema::create('ejecutables', function (Blueprint $table) {
            $table->unsignedBigInteger('numero');
            $table->string('descripcion', 50)->default('')->nullable(false);
            $table->string('ruta_archivo')->default('')->nullable(false);
            $table->string('icono')->default('')->nullable(true);
            $table->string('baja', 1)->default('')->nullable(true);
            $table->primary(['numero']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ejecutables');
    }
};
