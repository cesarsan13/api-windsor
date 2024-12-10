<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'materia',
        'secuencia',
        'matDescripcion',
        'descripcion',
        'evaluaciones',
        'EB1',
        'EB2',
        'EB3',
        'EB4',
        'EB5',
        'baja',
    ];
    protected $table = 'actividades';
    // protected $primaryKey = ['materia','secuencia'];
    // public function getKeyName()
    // {
    //     return $this->primaryKey;
    // }
}
