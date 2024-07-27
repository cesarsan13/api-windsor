<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabRepCobr extends Model
{
    use HasFactory;
    protected $fillable =[
        'recibo',
        'fecha',
        'articulo',
        'documento',
        'alumno',
        'nombre',
        'importe'
    ];
    protected $primaryKey = ['recibo', 'fecha', 'articulo', 'documento', 'alumno'];
    protected $attributes =[
        'recibo'=>'0',
        'fecha'=>'',
        'articulo'=>'0',
        'documento'=>'0',
        'alumno'=>'0',
        'nombre'=>'',
        'importe'=>'0'
    ];
}
