<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'nombre',
        'longitud',
        'baja',
    ];

    protected $attributes = [
        'numero' => '0',
        'nombre' => '',
        'longitud' => 0,
        'baja'=>'',
    ];
    protected $table = 'facturas_formas';
    protected $primaryKey = 'numero';
}
