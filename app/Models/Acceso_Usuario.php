<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso_Usuario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'id_punto_menu',
        't_a',
        'altas',
        'bajas',
        'cambios',
        'impresion',
    ];
    protected $table = 'acceso_usuarios';
}
