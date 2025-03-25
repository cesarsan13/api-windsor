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
        if (!Schema::connection('dynamic')->hasTable('tipo_cobro')) {
        Schema::create('tipo_cobro', function (Blueprint $table) {
            $table->unsignedBigInteger('numero')->primary(); 
            $table->string("descripcion",50)->default('')->nullable(false); 
            $table->float("comision")->default(0)->nullable(false); 
            $table->string("aplicacion",30)->default('')->nullable(false); 
            $table->string("cue_banco",34)->default('')->nullable(false);
            $table->string("baja",1)->default('')->nullable(false);
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_cobro');
    }
};
