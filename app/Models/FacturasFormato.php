<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturasFormato extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table ='facturas_formato';

    protected $fillable =[
        'numero_forma',
        'numero_dato',
        'forma_renglon',
        'forma_columna',
        'forma_renglon_dos',
        'forma_columna_dos',
        'numero_archivo',
        'nombre_campo',
        'longitud',
        'tipo_campo',
        'descripcion_campo',
        'formato',
        'cuenta',
        'funcion',
        'naturaleza',
        'tiempo_operacion',
        'renglon_impresion',
        'columna_impresion',
        'font_nombre',
        'font_tamaño',
        'font_bold',
        'font_italic',
        'font_subrayado',
        'font_rallado',
        'visible',
        'importe_transaccion',
    ];
      protected $primaryKey = 'numero_forma,numero_dato';
    protected $attributes = [
        'numero_forma'=>'0',
        'numero_dato'=>'0',
        'forma_renglon'=>'0',
        'forma_columna'=>'0',
        'forma_renglon_dos'=>'0',
        'forma_columna_dos'=>'0',
        'numero_archivo'=>'0',
        'nombre_campo'=>'',
        'longitud'=>'0',
        'tipo_campo'=>'0',
        'descripcion_campo'=>'',
        'formato'=>'0',
        'cuenta'=>'0',
        'funcion'=>'0',
        'naturaleza'=>'0',
        'tiempo_operacion'=>'0',
        'renglon_impresion'=>'0',
        'columna_impresion'=>'0',
        'font_nombre'=>'',
        'font_tamaño'=>'0',
        'font_bold'=>'',
        'font_italic'=>'',
        'font_subrayado'=>'',
        'font_rallado'=>'',
        'visible'=>'',
        'importe_transaccion'=>'0',
    ];
}
