<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'nombre',
        'a_paterno',
        'a_materno',
        'a_nombre',
        'fecha_nac',
        'fecha_inscripcion',
        'fecha_baja',
        'sexo',
        'telefono1',
        'telefono2',
        'celular',
        'codigo_barras',
        'direccion',
        'colonia',
        'ciudad',
        'estado',
        'cp',
        'email',
        'ruta_foto',
        'dia_1',
        'dia_2',
        'dia_3',
        'dia_4',
        'hora_1',
        'hora_2',
        'hora_3',
        'hora_4',
        'cancha_1',
        'cancha_2',
        'cancha_3',
        'cancha_4',
        'horario_1',
        'horario_2',
        'horario_3',
        'horario_4',
        'horario_5',
        'horario_6',
        'horario_7',
        'horario_8',
        'horario_9',
        'horario_10',
        'horario_11',
        'horario_12',
        'horario_13',
        'horario_14',
        'horario_15',
        'horario_16',
        'horario_17',
        'horario_18',
        'horario_19',
        'horario_20',
        'cond_1',
        'cond_2',
        'cond_3',
        'nom_pediatra',
        'tel_p_1',
        'tel_p_2',
        'cel_p_1',
        'tipo_sangre',
        'alergia',
        'aseguradora',
        'poliza',
        'tel_ase_1',
        'tel_ase_2',
        'razon_social',
        'raz_direccion',
        'raz_colonia',
        'raz_ciudad',
        'raz_estado',
        'raz_cp',
        'nom_padre',
        'tel_pad_1',
        'tel_pad_2',
        'cel_pad',
        'nom_madre',
        'tel_mad_1',
        'tel_mad_2',
        'cel_mad',
        'nom_avi',
        'tel_avi_1',
        'tel_avi_2',
        'cel_avi_1',
        'ciclo_escolar',
        'descuento',
        'rfc_factura',
        'estatus',
        'escuela',
        'grupo',
        'baja',
    ];
    protected $table = 'alumnos';
    protected $primaryKey = 'numero';
}
