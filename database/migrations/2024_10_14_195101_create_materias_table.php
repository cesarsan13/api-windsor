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
        Schema::create('materias', function (Blueprint $table) {
            $table->unsignedBigInteger('numero')->primary();
            $table->string('descripcion', 100)->default('');
            $table->integer('evaluaciones')->default(0);
            $table->string('actividad', 10)->default('');
            $table->integer('area')->default(0);
            $table->integer('orden')->default(0);
            $table->string('lenguaje', 15)->default('');
            $table->string('caso_evaluar', 15)->default('');
            $table->string('baja', 1)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
