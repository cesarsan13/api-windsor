<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'comentarios';

    protected $fillable = [
        'id',
        'comentario_1',
        'comentario_2',
        'comentario_3',
        'baja',
        'generales',
    ];

    protected $primaryKey = 'id';
    protected $attributes = [
        'id' => '0',
        'comentario_1' => '',
        'comentario_2' => '',
        'comentario_3' => '',
        'baja' => '',
        'generales' => '',
    ];
}
