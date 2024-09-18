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
        Schema::create('facturas_formato', function (Blueprint $table) {
            $table->unsignedBigInteger('numero_forma')->nullable(false)->default(0); //(11)
            $table->bigInteger('numero_dato')->nullable(false)->default(0); //(11)
            $table->primary(['numero_forma', 'numero_dato']);
            $table->double('forma_renglon')->nullable(false)->default(0);
            $table->double('forma_columna')->nullable(false)->default(0);
            $table->double('forma_renglon_dos')->nullable(false)->default(0);
            $table->double('forma_columna_dos')->nullable(false)->default(0);
            $table->bigInteger('numero_archivo')->nullable(false)->default(0); //(11)
            $table->string('nombre_campo',35)->default('')->nullable(false) ;
            $table->double('longitud')->nullable(false)->default(0);
            $table->bigInteger('tipo_campo')->nullable(false)->default(0);
            $table->string('descripcion_campo',240)->nullable(false)->default('');
            $table->bigInteger('formato')->nullable(false)->default(0); //(11)
            $table->bigInteger('cuenta')->nullable(false)->default(0); //(11)
            $table->bigInteger('funcion')->nullable(false)->default(0); //(11)
            $table->bigInteger('naturaleza')->nullable(false)->default(0); //(11)
            $table->bigInteger('tiempo_operacion')->nullable(false)->default(0); //(11)
            $table->bigInteger('renglon_impresion')->nullable(false)->default(0); //(11)
            $table->bigInteger('columna_impresion')->nullable(false)->default(0); //(11)
            $table->string('font_nombre',20)->nullable(false)-> default('');
            $table->bigInteger('font_tamaño')->nullable(false)->default(0); //smallint(6)
            $table->string('font_bold',1)->nullable(false)->default('');
            $table->string('font_italic',1)->nullable(false)->default('');
            $table->string('font_subrallado',1)->nullable(false)->default('');
            $table->string('font_rallado',1)->nullable(false)->default('');
            $table->string('visible',1)->nullable(false)->default('');
            $table->double('importe_transaccion')->nullable(false)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_formato');
    }
};
