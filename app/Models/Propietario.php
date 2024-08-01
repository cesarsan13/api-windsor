<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'nombre',
        'clave_seguridad',
        'busqueda_max',
        'inscripcion',
        'con_recibos',
        'con_facturas',
        'clave_bonificacion',
    ];
    protected $primaryKey = 'numero';
    protected $table = 'propietario';
}
