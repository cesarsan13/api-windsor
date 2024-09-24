<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobranza_Diaria extends Model
{
    use HasFactory;

    protected $table ='cobranza_diaria';
    protected $primaryKey = 'recibo';
    public $timestamps = false;
    protected $fillable = [
        'recibo', 
        'fecha_cobro',
        'hora',
        'alumno',
        'importe_cobro',
        'tipo_pago_1',
        'importe_pago_1',
        'referencia_1',
        'tipo_pago_2',
        'importe_pago_2',
        'referencia_2',
        'cajero',
        'quien_paga',
        'comentario',
        'comentario_ad',
        'cuen_banco',
        'referencia',
        'importe',
    ];
    protected $attributes = [
        'recibo' => '0', 
        'fecha_cobro' => '',
        'hora' => '',
        'alumno' => '',
        'importe_cobro'=> '0',
        'tipo_pago_1' => '',
        'importe_pago_1'=> '0',
        'referencia_1' => '',
        'tipo_pago_2' => '',
        'importe_pago_2'=> '0',
        'referencia_2' => '',
        'cajero' => '',
        'quien_paga' => '',
        'comentario' => '',
        'comentario_ad' => '',
        'cuen_banco' => '',
        'referencia' => '',
        'importe' => '0',
    ];
}