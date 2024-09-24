<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encab_Pedido extends Model
{
    use HasFactory;
    protected $table = 'encab_pedido';
    public $timestamps = false;
    protected $primaryKey = 'recibo';
    protected $fillable = [
        'recibo',
        'fecha',
        'alumno',
        'cajero',
        'importe_total',
        'tipo_pago_1',
        'importe_pago_1',
        'referencia_1',
        'tipo_pago_2',
        'importe_pago_2',
        'referencia_2',
        'nombre_quien',
        'comentario',
        'comentario_ad',
        'facturado',
        'numero_factura',
        'fecha_factura',
    ];
    /**
     * Get the alumno associated with the EncabPedido
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'alumnos', 'numero');
    }
    /**
     * Get the cajero associated with the EncabPedido
     */
    public function cajero()
    {
        return $this->belongsTo(Cajeros::class, 'cajeros', 'numero');
    }
}
