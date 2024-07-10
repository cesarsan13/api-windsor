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
        Schema::create('productos', function (Blueprint $table) {
            $table->bigInteger('id')->primary()->default(0);
            $table->string('descripcion')->nullable(false)->default('');
            $table->double('costo')->nullable(false)->default(0);
            $table->string('frecuencia', 20)->nullable(false)->default('');
            $table->double('pro_recargo')->nullable(false)->default(0);
            $table->string('aplicacion', 25)->nullable(false)->default('');
            $table->double('iva')->nullable(false)->default(0);
            $table->integer('cond_1')->nullable(false)->default(0);
            $table->integer('cam_precio')->nullable(false)->default(0);
            $table->string('ref', 20)->nullable(false)->default(0);
            $table->string('baja', 1)->nullable(false)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};