<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenus extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'id_acceso',
        'descripcion',
        'baja',
    ];
    protected $table = 'sub_menus';
    protected $primarykey = 'numero';
    public function accesoMenu()
    {
        return $this->belongsTo(AccesosMenu::class, 'id_acceso', 'numero');
    }
}
