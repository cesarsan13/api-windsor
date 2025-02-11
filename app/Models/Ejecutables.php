<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejecutables extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'descripcion',
        'ruta_archivo',
        'icono',
        'baja',
    ];
    protected $table = 'ejecutables';
    protected $primaryKey = 'numero';
}
