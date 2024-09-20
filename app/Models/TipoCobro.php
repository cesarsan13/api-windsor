<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCobro extends Model
{
    use HasFactory;
    public $timestamps = false;

     protected $table = 'tipo_cobro';
    protected $fillable = [
        'numero',
        'descripcion',
        'comision',
        'aplicacion',
        'baja',
        'cue_banco',
    ];
    protected $primaryKey = 'numero';
    protected $attributes = [
        'numero' => '0',
        'descripcion' => '',
        'comision' => '0',
        'aplicacion' => '',
        'baja' => '',
        'cue_banco' => '',
    ];
}
