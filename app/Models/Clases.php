<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clases extends Model
{
    use HasFactory;

    protected $fillable = [
        'grupo',
        'materia',
        'profesor',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'baja',
    ];
    protected $attributes = [
        'grupo' => '0',
        'materia' => '0',
        'profesor' => '0',
        'lunes' => '',
        'martes' => '',
        'miercoles' => '',
        'jueves' => '',
        'viernes' => '',
        'sabado' => '',
        'domingo' => '',
        'baja' => '',
    ];
    public $timestamps = false;
    protected $table ='clases';
}
