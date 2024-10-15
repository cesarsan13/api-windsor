<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model
{
    use HasFactory;
    protected $fillable = [
        'bimestre',
        'grupo',
        'alumno',
        'materia',
        'actividad',
        'unidad',
        'calificacion',
        'baja',
    ];
    protected $table = 'calificaciones';
    public $timestamps = false;
    // protected $primaryKey = ['bimestre', 'grupo', 'actividad', 'unidad'];
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumnos', 'numero');
    }
}
