<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    protected $fillable=[
        'numero',
        'cancha',
        'dia',
        'horario',
        'max_niños',
        'sexo',
        'edad_ini',
        'edad_fin',
        'baja'
    ];
    protected $table = 'horarios';
    protected $primaryKey='numero';
    public $timestamps = false;
}
