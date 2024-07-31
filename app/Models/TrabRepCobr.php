<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabRepCobr extends Model
{
    use HasFactory;
    protected $fillable =[
        'recibo',
        'fecha',
        'articulo',
        'documento',
        'alumno',
        'nombre',
        'importe'
    ];
    protected $table = 'trab_rep_cobr'; 
    public $incrementing = false;
    protected $primaryKey = ['recibo', 'fecha', 'articulo', 'documento', 'alumno'];

    
    public function getKeyName()
    {
        return $this->primaryKey;
    }
    // protected $attributes =[
    //     'recibo'=>'0',
    //     'fecha'=>'',
    //     'articulo'=>'0',
    //     'documento'=>'0',
    //     'alumno'=>'0',
    //     'nombre'=>'',
    //     'importe'=>'0'
    // ];
}
