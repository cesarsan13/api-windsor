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
        Schema::create('rep_dos_sels', function (Blueprint $table) {
            $table->bigInteger('numero')->primary()->default(0);
            $table->integer('numero_1')->default(0)->nullable(false);
            $table->string('nombre_1',50)->default('')->nullable(false);
            $table->string('año_nac_1',15)->default('')->nullable(false);
            $table->string('mes_nac_1',15)->default('')->nullable(false);
            $table->string('telefono_1',15) -> default('')->nullable(false);
            $table->integer('numero_2')->default(0)->nullable(false);
            $table->string('nombre_2',50)->default('')->nullable(false);
            $table->string('año_nac_2',15)->default('')->nullable(false);
            $table->string('mes_nac_2',15)->default('')->nullable(false);
            $table->string('telefono_2',15) -> default('')->nullable(false);
            $table->char('baja',1)->default('')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rep_dos_sels');
    }
};
