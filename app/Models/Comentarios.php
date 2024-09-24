<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'comentario';

    protected $fillable = [
        'numero',
        'comentario_1',
        'comentario_2',
        'comentario_3',
        'baja',
        'generales',
    ];

    protected $primaryKey = 'numero';
    protected $attributes = [
        'numero' => '0',
        'comentario_1' => '',
        'comentario_2' => '',
        'comentario_3' => '',
        'baja' => '',
        'generales' => '',
    ];
}
