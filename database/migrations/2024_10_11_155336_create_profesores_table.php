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
        if (!Schema::connection('dynamic')->hasTable('profesores')) {
        Schema::create('profesores', function (Blueprint $table) {
            $table->unsignedBigInteger('numero')->primary();
            $table->string('nombre', 50)->default('')->nullable(false);
            $table->string('ap_paterno', 50)->default('')->nullable(false);
            $table->string('ap_materno', 50)->default('')->nullable(false);
            $table->string('direccion', 50)->default('')->nullable(false);
            $table->string('colonia', 50)->default('')->nullable(false);
            $table->string('ciudad', 50)->default('')->nullable(false);
            $table->string('estado', 20)->default('')->nullable(false);
            $table->string('cp', 6)->default('')->nullable(false);
            $table->string('pais', 50)->default('')->nullable(false);
            $table->string('rfc', 20)->default('')->nullable(false);
            $table->string('telefono_1', 20)->default('')->nullable(false);
            $table->string('telefono_2', 20)->default('')->nullable(false);
            $table->string('fax', 20)->default('')->nullable(false);
            $table->string('celular', 20)->default('')->nullable(false);
            $table->string('email', 80)->default('')->nullable(false);
            $table->string('contraseña')->default('')->nullable(false);
            $table->string('baja', 1)->default('')->nullable(false);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
