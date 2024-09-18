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
        Schema::create('cajeros', function (Blueprint $table) {
            $table->bigInteger('numero')-> primary()->default(0); //(11)
            $table->string('nombre',50) -> default(''); //(35)
            $table->string('direccion',50) -> default(''); 
            $table->string('colonia',50) -> default(''); //(30)
            $table->string('estado',50) -> default(''); //(30)
            $table->string('telefono',25) -> default(''); //(20)
            $table->string('fax',50) -> default(''); //(20)
            $table->string('mail',50) -> default(''); //(40)
            $table->string('baja',1) -> default(''); 
            $table->string('fec_cambio',50) -> default(''); //(11)
            $table->string('clave_cajero',50) -> default(''); //(8)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajeros');
    }
};
