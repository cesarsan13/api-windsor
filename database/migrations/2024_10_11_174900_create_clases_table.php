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
        Schema::create('clases', function (Blueprint $table) {
            $table->integer('grupo');
            $table->integer('materia');   
            $table->integer('profesor');  
            $table->string('lunes',15)->default('')->nullable(false);
            $table->string('martes',15)->default('')->nullable(false);
            $table->string('miercoles',15)->default('')->nullable(false);
            $table->string('jueves',15)->default('')->nullable(false);
            $table->string('viernes',15)->default('')->nullable(false);
            $table->string('sabado',15)->default('')->nullable(false);
            $table->string('domingo',15)->default('')->nullable(false);
            $table->string('baja',1)->default('')->nullable(false);
            $table->timestamps();
            $table->primary(['grupo', 'materia']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
