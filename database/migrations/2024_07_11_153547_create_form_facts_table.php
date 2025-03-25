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
        if (!Schema::connection('dynamic')->hasTable('facturas_formas')) {
        Schema::create('facturas_formas', function (Blueprint $table) {
            $table->bigInteger('numero')-> primary()->default(0); 
            $table->string('nombre',50) -> default(''); 
            $table->double('longitud')->nullable(false)->default(0);
            $table->string('baja',1) -> default('');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_formas');
    }
};
