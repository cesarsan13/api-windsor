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
            $table->bigInteger('numero')->primary()->default(0); //(11)
            $table->integer('cancha')->default(0)->nullable(false); //(11)
            $table->string('dia',50)->default('')->nullable(false); //(15)
            $table->string('horario',50)->default('')->nullable(false); //(20)
            $table->integer('max_niños')->default(0)->nullable(false); //(11)
            $table->string('sexo',50)->default('')->nullable(false); //(8)
            $table->integer('edad_ini')->default(0)->nullable(false); //(11)
            $table->integer('edad_fin')->default(0)->nullable(false); //(11)
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
