<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasesDatos extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'nombre',
        'host',
        'post',
        'username',
        'password',
        'clave_propietario',
        'proyecto',
    ];
    protected $primaryKey = 'id';
    protected $table = 'bases_datos';
}
