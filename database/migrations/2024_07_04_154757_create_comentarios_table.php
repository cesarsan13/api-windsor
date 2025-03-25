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
        if (!Schema::connection('dynamic')->hasTable('comentarios')) {
        Schema::create('comentarios', function (Blueprint $table) { 
            $table->unsignedBigInteger('numero')->primary(); 
            $table->string('comentario_1',50)->default('')->nullable(false);
            $table->string('comentario_2',50)->default('')->nullable(false);
            $table->string('comentario_3',50)->default('')->nullable(false);
            $table->string('generales',1)->default('')->nullable(false); 
            $table->string('baja',1)->default('')->nullable(false);
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
