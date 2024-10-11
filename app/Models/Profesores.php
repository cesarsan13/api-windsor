<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesores extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'direccion',
        'colonia',
        'ciudad',
        'estado',
        'cp',
        'pais',
        'rfc',
        'telefono_1',
        'telefono_2',
        'fax',
        'celular',
        'email',
        'contraseña',
        'baja',
    ];
    protected $table = 'profesores';
    protected $primaryKey = 'numero';
}
