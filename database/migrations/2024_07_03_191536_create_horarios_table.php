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
        Schema::create('horarios', function (Blueprint $table) {            
            $table->bigInteger('numero')->primary()->default(0);
            $table->integer('cancha')->default(0)->nullable(false);
            $table->string('dia',50)->default('')->nullable(false);
            $table->string('horario',50)->default('')->nullable(false);
            $table->integer('max_niños')->default(0)->nullable(false);
            $table->string('sexo',50)->default('')->nullable(false);
            $table->integer('edad_ini')->default(0)->nullable(false);
            $table->integer('edad_fin')->default(0)->nullable(false);
            $table->char('baja',1)->default('')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
