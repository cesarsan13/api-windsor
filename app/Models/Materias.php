<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'descripcion',
        'evaluaciones',
        'actividad',
        'area',
        'orden',
        'lenguaje',
        'caso_evaluar',
        'baja',
    ];
    protected $table = 'materias';
    protected $primaryKey = 'numero';
}
