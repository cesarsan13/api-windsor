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
        if (!Schema::connection('dynamic')->hasTable('configuracion')) {
        Schema::create('configuracion', function (Blueprint $table) {
            $table->bigInteger('numero_configuracion')-> primary()->default(0); 
            $table->string('descripcion_configuracion',50) -> default('');
            $table->integer('valor_configuracion')->default(0)->nullable(false);
            $table->string('texto_configuracion',70) -> default(''); 
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion');
    }
};
