<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplicacion1 extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'aplicacion_1';

    protected $fillable = [
        'numero',
        'numero_cuenta',
        'cargo_abono',
        'importe_movimiento',
        'referencia',
        'fecha_referencia',
    ];

    protected $primaryKey = 'numero';
    protected $attributes = [
        'numero' => '0',
        'numero_cuenta' => '',
        'cargo_abono' => '',
        'importe_movimiento' => '',
        'referencia' => '',
        'fecha_referencia' => '',
    ];
}
