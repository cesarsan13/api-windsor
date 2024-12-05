<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class punto_menuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [
                'clave_punto' => 1,
                'descripcion_punto' => 'Catálogos'
            ],
            [
                'clave_punto' => 2,
                'descripcion_punto' => 'Procesos' 
            ],
            [
                'clave_punto' => 3,
                'descripcion_punto' => 'Reportes'
            ],
            [
                'clave_punto' => 4,
                'descripcion_punto' => 'Utilerías'
            ],
        ];

        foreach ($registros as $registro) {
            DB::table('punto_menu')->updateOrInsert(
                [
                    'clave_punto'=>$registro['clave_punto'],
                    'descripcion_punto'=>$registro['descripcion_punto']
                ],
                $registro
            );

        }
    }
}