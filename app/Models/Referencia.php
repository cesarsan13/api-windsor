<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'numero',
        'referencia',
        'descripcion',
        'baja',
    ];
    protected $primaryKey = 'numero';
    protected $table = 'referencias';
}
