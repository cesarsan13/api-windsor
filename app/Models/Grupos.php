<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'grupo',
        'salon',
        'cve_seg',
        'fecha_seg',
        'hora_seg',
        'baja',
        'profesor',
        'baja',
    ];
    protected $table = 'grupos';
}
