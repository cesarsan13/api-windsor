<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'descripcion',
        'costo',
        'frecuencia',
        'pro_recargo',
        'aplicacion',
        'iva',
        'cond_1',
        'cam_precio',
        'ref',
        'baja',
    ];
    protected $attributes = [
        'descripcion' => '',
        'costo' => 0,
        'frecuencia' => '',
        'pro_recargo' => 0,
        'aplicacion' => '',
        'iva' => 0,
        'cond_1' => '',
        'cam_precio' => '',
        'ref' => '',
        'baja' => '',
    ];
    protected $table = 'productos';
    protected $primaryKey = 'id';
}
