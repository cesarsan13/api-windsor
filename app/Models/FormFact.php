<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormFact extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_forma',
        'nombre_forma',
        'longitud',
        'baja',
    ];

    protected $attributes = [
        'numero_forma' => '0',
        'nombre_forma' => '',
        'longitud' => 0,
        'baja'=>'',
    ];
    public $timestamps = false;
    protected $table = 'facturas_formas';
    protected $primaryKey = 'numero_forma';
}
