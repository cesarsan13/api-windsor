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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->integer('bimestre')->default(0);
            $table->string('grupo', 15)->default('');
            $table->integer('alumno')->default('');
            $table->integer('materia')->default(0);
            $table->integer('actividad')->default(0);
            $table->integer('unidad')->default(0);
            $table->decimal('calificacion', 8, 2)->default(0);
            $table->string('baja', 1)->default('');
            $table->primary(['bimestre', 'grupo', 'alumno', 'materia', 'actividad', 'unidad']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
