<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobranzaDiaria extends Model
{
    use HasFactory;

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
        'importe'
    ];
    // protected $primaryKey = ['recibo', 'fecha_cobro', 'hora'];
    protected $table = 'cobranza_diaria';
}
