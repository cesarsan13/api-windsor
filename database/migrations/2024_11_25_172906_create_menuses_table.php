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
        if (!Schema::connection('dynamic')->hasTable('menus')) {
        Schema::create('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('numero')->primary();
            $table->string('nombre', 80)->default('')->nullable(false);
            $table->string('baja', 1)->default('');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
