<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [
                'id' => 9,
                'descripcion' => 'COL MAT 12M',
                'costo' => 1365,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 10060007,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 9,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 10,
                'descripcion' => 'COL KI 12M',
                'costo' => 1628,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 1006008,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 10,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 11,
                'descripcion' => 'COL KII 12M',
                'costo' => 1773,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 10060009,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 11,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 24,
                'descripcion' => 'COL MAT 10M',
                'costo' => 1638,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 10060007,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 24,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 26,
                'descripcion' => 'COL KI 10M',
                'costo' => 1954,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 1006008,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 26,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 28,
                'descripcion' => 'COL KII 10M',
                'costo' => 2128,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => 10060009,
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 28,
                'cam_Precio' => 0,
                'ref' => 'COL'
            ],
            [
                'id' => 9999,
                'descripcion' => 'RECARGO',
                'costo' => 255,
                'frecuencia' => '',
                'pro_recargo' => 0,
                'aplicacion' => '',
                'iva' => 0,
                'baja' => 'n',
                'cond_1' => 9999,
                'cam_Precio' => 0,
                'ref' => 'REC'
            ],
        ];        
        foreach ($registros as $registro){
            DB::table('productos')->updateOrInsert(
                ['id'=>$registro['id']],
                $registro
            );
        }        
    }
}
