<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsCobranza extends Model
{
    protected $fillable = [
        'alumno',
        'producto',
        'numero_doc',
        'fecha',
        'descuento',
        'importe',
        'fecha_cobro',
        'importe_pago',
        'ref',
        'grupo',
        'orden',
        'baja',
    ];
    protected $table = 'documentos_cobranza';
    // protected $primaryKey = ['alumno'];
}
