<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clases extends Model
{
    use HasFactory;
    public $timestamps = false;
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
    protected $table = 'clases';
}
