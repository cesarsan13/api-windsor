<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'recibo',
        'alumno',
        'articulo',
        'documento',
        'fecha',
        'cantidad',
        'precio_unitario',
        'descuento',
        'iva',
        'numero_factura',
    ];
    protected $table = 'detalle_pedido';
    // protected $primaryKey = ['alumno'];
}
