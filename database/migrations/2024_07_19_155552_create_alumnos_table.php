<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::connection('dynamic')->hasTable('alumnos')) {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigInteger('numero')->primary()->default(0); //(11)
            $table->string('nombre', 50)->nullable(false)->default('');
            $table->string('a_paterno', 50)->nullable(false)->default(''); //(20)
            $table->string('a_materno', 50)->nullable(false)->default(''); //(20)
            $table->string('a_nombre', 50)->nullable(true)->default(''); //(20)
            $table->string('fecha_nac', 15)->nullable(false)->default(''); //(11)
            $table->string('fecha_inscripcion', 15)->nullable(false)->default(''); //(11)
            $table->string('fecha_baja', 15)->nullable(true)->default(''); //(11)
            $table->string('sexo', 15)->nullable(false)->default(''); //(2)
            $table->string('telefono1', 15)->nullable(false)->default(''); //(20)
            $table->string('telefono2', 15)->nullable(true)->default(''); //(20)
            $table->string('celular', 15)->nullable(false)->default(''); //(20)
            $table->string('codigo_barras')->nullable(true)->default(''); //(20)
            $table->string('direccion')->nullable(false)->default(''); //(50)
            $table->string('colonia', 100)->nullable(false)->default(''); //(30)
            $table->string('ciudad', 100)->nullable(false)->default(''); //(20)
            $table->string('estado', 100)->nullable(false)->default(''); //(20)
            $table->string('cp', 10)->nullable(false)->default(''); //(6)
            $table->string('email')->nullable(false)->default(''); //(40)
            $table->string('imagen', )->nullable(true)->default(''); //(100)
            $table->string('dia_1', 20)->nullable(true)->default('');
            $table->string('dia_2', 20)->nullable(true)->default('');
            $table->string('dia_3', 20)->nullable(true)->default('');
            $table->string('dia_4', 20)->nullable(true)->default('');
            $table->bigInteger('hora_1')->nullable(true)->default(0); //(15)
            $table->bigInteger('hora_2')->nullable(true)->default(0); //(15)
            $table->bigInteger('hora_3')->nullable(true)->default(0); //(15)
            $table->bigInteger('hora_4')->nullable(true)->default(0); //(15)
            $table->bigInteger('cancha_1')->nullable(true)->default(0); //(11)
            $table->bigInteger('cancha_2')->nullable(true)->default(0); //(11)
            $table->bigInteger('cancha_3')->nullable(true)->default(0); //(11)
            $table->bigInteger('cancha_4')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_1')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_2')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_3')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_4')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_5')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_6')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_7')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_8')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_9')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_10')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_11')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_12')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_13')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_14')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_15')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_16')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_17')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_18')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_19')->nullable(true)->default(0); //(11)
            $table->bigInteger('horario_20')->nullable(true)->default(0); //(11)
            $table->bigInteger('cond_1')->nullable(true)->default(0); //(11)
            $table->bigInteger('cond_2')->nullable(true)->default(0); //(11)
            $table->bigInteger('cond_3')->nullable(true)->default(0); //(11)
            $table->string('nom_pediatra', 50)->nullable(true)->default(''); //(60)
            $table->string('tel_p_1', 15)->nullable(true)->default('');
            $table->string('tel_p_2', 15)->nullable(true)->default('');
            $table->string('cel_p_1', 15)->nullable(true)->default('');
            $table->string('tipo_sangre', 20)->nullable(true)->default(''); //(15)
            $table->string('alergia', 50)->nullable(true)->default(''); //(60)
            $table->string('aseguradora', 100)->nullable(true)->default(''); //(60)
            $table->string('poliza', 30)->nullable(true)->default(''); //(60)
            $table->string('tel_ase_1', 15)->nullable(true)->default('');
            $table->string('tel_ase_2', 15)->nullable(true)->default('');
            $table->string('razon_social', 30)->nullable(true)->default(''); //(70)
            $table->string('raz_direccion')->nullable(true)->default(''); //(50)
            $table->string('raz_colonia', 100)->nullable(true)->default(''); //(30)
            $table->string('raz_ciudad', 100)->nullable(true)->default(''); //(20)
            $table->string('raz_estado', 100)->nullable(true)->default(''); //(20)
            $table->string('raz_cp', 10)->nullable(true)->default(''); //(11)
            $table->string('nom_padre', 100)->nullable(true)->default(''); //(50)
            $table->string('tel_pad_1', 15)->nullable(true)->default('');
            $table->string('tel_pad_2', 15)->nullable(true)->default('');
            $table->string('cel_pad', 15)->nullable(true)->default('');
            $table->string('nom_madre', 100)->nullable(true)->default(''); //(50)
            $table->string('tel_mad_1', 15)->nullable(true)->default('');
            $table->string('tel_mad_2', 15)->nullable(true)->default('');
            $table->string('cel_mad', 15)->nullable(true)->default('');
            $table->string('nom_avi', 100)->nullable(true)->default(''); //(50)
            $table->string('tel_avi_1', 15)->nullable(true)->default('');
            $table->string('tel_avi_2', 15)->nullable(true)->default('');
            $table->string('cel_avi', 15)->nullable(true)->default('');
            $table->string('ciclo_escolar', 50)->nullable(true)->default(''); //(10)
            $table->double('descuento')->nullable(true)->default(0);
            $table->string('rfc_factura', 50)->nullable(true)->default(''); //(13)
            $table->string('estatus', 20)->nullable(false)->default(''); //(25)
            $table->string('escuela', 50)->nullable(true)->default('');
            $table->string('baja', 1)->nullable(false)->default('');
            $table->string('ruta_foto', 250)->nullable(false)->default('');
            $table->string('grupo', 15)->nullable(false)->default('');
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
