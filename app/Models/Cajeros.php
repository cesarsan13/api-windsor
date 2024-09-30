<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajeros extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'nombre',
        'direccion',
        'colonia',
        'estado',
        'telefono',
        'fax',
        'mail',
        'baja',
        'fec_cambio',
        'clave_cajero',
    ];
    protected $attributes = [
        'numero'=>'0',
        'nombre'=>'',
        'direccion'=>'',
        'colonia'=>'',
        'estado'=>'',
        'telefono'=>'',
        'fax'=>'',
        'mail'=>'',
        'baja'=>'',
        'fec_cambio'=>'',
        'clave_cajero'=>'0',
    ];
    public $timestamps = false;
    protected $table ='cajeros';
    protected $primaryKey = 'numero';
}
