<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccesosMenu extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'ruta',
        'descripcion',
        'icono',
        'menu',
        'baja',
    ];
    protected $table = 'accesos_menu';
    // protected $primaryKey = 'numero';
}
