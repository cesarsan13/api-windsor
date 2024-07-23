<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepDosSel extends Model
{
     
    use HasFactory;
    protected $fillable = [
        'numero',
        'numero_1',
        'nombre_1',
        'año_nac_1',
        'mes_nac_1',
        'telefono_1',
        'numero_2',
        'nombre_2',
        'año_nac_2',
        'mes_nac_2',
        'telefono_2',
        'baja',
    ];
    protected $attributes = [
        'numero'=>'0',
        'numero_1'=>'0',
        'nombre_1'=>'',
        'año_nac_1'=>'',
        'mes_nac_1'=>'',
        'telefono_1'=>'',
        'numero_2'=>'0',
        'nombre_2'=>'',
        'año_nac_2'=>'',
        'mes_nac_2'=>'',
        'telefono_2'=>'',
        'baja'=>'',
    ];
    protected $table='rep_dos_sel';
    protected $primarykey = 'numero';
}
