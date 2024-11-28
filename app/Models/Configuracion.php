<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model 
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'numero_configuracion',
        'descripcion_configuracion',
        'valor_configuracion',
        'texto_configuracion'
    ];
    protected $table = 'configuracion';
}