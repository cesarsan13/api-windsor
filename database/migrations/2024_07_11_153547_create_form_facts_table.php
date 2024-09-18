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
        Schema::create('facturas_formas', function (Blueprint $table) {
            $table->bigInteger('numero')-> primary()->default(0); //(11)
            $table->string('nombre',50) -> default(''); //(30)
            $table->double('longitud')->nullable(false)->default(0);
            $table->string('baja',1) -> default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_formas');
    }
};
