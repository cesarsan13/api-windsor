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
        if (!Schema::connection('dynamic')->hasTable('actividades')) {
        Schema::create('actividades', function (Blueprint $table) {
            $table->integer('materia')->default(0);
            $table->string('matDescripcion', 100)->nullable(false)->default('');
            $table->integer('secuencia')->default(0);
            $table->string('descripcion',30)->default('');
            $table->integer('evaluaciones')->default(0);
            $table->integer('EB1')->default(0);
            $table->integer('EB2')->default(0);
            $table->integer('EB3')->default(0);
            $table->integer('EB4')->default(0);
            $table->integer('EB5')->default(0);
            $table->string('baja',1)->default('');
            $table->primary(['materia','secuencia']);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
