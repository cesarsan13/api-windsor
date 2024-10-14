<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaturas extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'descripcion',
        'fecha_seg',
        'hora_seg',
        'cve_seg',
        'baja',
        'evaluaciones',
        'actividad',
        'area',
        'orden',
        'lenguaje',
        'caso_evaluar',
    ];
    protected $attributes = [
        'numero' => 0,
        'descripcion' => '',
        'fecha_seg' => '',
        'hora_seg' => '',
        'cve_seg' => '',
        'baja' => '',
        'evaluaciones' => 0,
        'actividad' => '',
        'area' => 0,
        'orden' => 0,
        'lenguaje' => '',
        'caso_evaluar' => '',
    ];
    protected $table = 'asignaturas';
    public $timestamps = false;
    protected $primaryKey = 'numero';
}
