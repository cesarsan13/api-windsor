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
        if (!Schema::connection('dynamic')->hasTable('asignaturas')) {
        Schema::create('asignaturas', function (Blueprint $table) {
            $table->bigInteger('numero')->primary()->default(0);
            $table->string('descripcion', 100)->nullable(false)->default('');
            $table->string('fecha_seg', 10)->nullable(false)->default('');
            $table->string('hora_seg', 10)->nullable(false)->default('');
            $table->string('cve_seg', 10)->nullable(false)->default('');
            $table->string('baja', 1)->nullable(false)->default('');
            $table->bigInteger('evaluaciones')->nullable(false)->default(0);
            $table->string('actividad', 10)->nullable(false)->default('');
            $table->bigInteger('area')->nullable(false)->default(0);
            $table->bigInteger('orden')->nullable(false)->default(0);
            $table->string('lenguaje', 15)->nullable(false)->default('');
            $table->string('caso_evaluar', 15)->nullable(false)->default('');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaturas');
    }
};
