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
        Schema::create('comentarios', function (Blueprint $table) { //comentario
            $table->unsignedBigInteger('id')->primary(); //(11)
            $table->string('comentario_1',50)->default('')->nullable(false);
            $table->string('comentario_2',50)->default('')->nullable(false);
            $table->string('comentario_3',50)->default('')->nullable(false);
            $table->string('generales',1)->default('')->nullable(false); //tinyint
            $table->string('baja',1)->default('')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
