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
        if (!Schema::connection('dynamic')->hasTable('sub_menus')) {
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->unsignedBigInteger('numero');
            $table->unsignedBigInteger('id_acceso');
            $table->string('descripcion', 50)->default('')->nullable(false);
            $table->string('baja', 1)->default('')->nullable(false);
            $table->primary(['numero', 'id_acceso']);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_menuses');
    }
};
