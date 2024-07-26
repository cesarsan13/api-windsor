<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [
                'numero' => 1,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'PREMATERNAL',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 2,
                'edad_fin' => 2,
                'baja' => '',
            ],
            [
                'numero' => 2,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'MATERNAL',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 2,
                'edad_fin' => 3,
                'baja' => '',
            ],
            [
                'numero' => 3,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'KINDER I',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 3,
                'edad_fin' => 4,
                'baja' => '',
            ],
            [
                'numero' => 4,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'KINDER II',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 4,
                'edad_fin' => 5,
                'baja' => '',
            ],
            [
                'numero' => 5,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'PREPRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 5,
                'edad_fin' => 6,
                'baja' => '',
            ],
            [
                'numero' => 6,
                'cancha' => 1,
                'dia' => '',
                'horario' => '1° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 6,
                'edad_fin' => 7,
                'baja' => '',
            ],
            [
                'numero' => 7,
                'cancha' => 1,
                'dia' => '',
                'horario' => '2° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 7,
                'edad_fin' => 8,
                'baja' => '',
            ],
            [
                'numero' => 8,
                'cancha' => 1,
                'dia' => '',
                'horario' => '3° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 8,
                'edad_fin' => 9,
                'baja' => '',
            ],
            [
                'numero' => 9,
                'cancha' => 1,
                'dia' => '',
                'horario' => '4° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 9,
                'edad_fin' => 10,
                'baja' => '',
            ],
            [
                'numero' => 10,
                'cancha' => 1,
                'dia' => '',
                'horario' => '5° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 10,
                'edad_fin' => 11,
                'baja' => '',
            ],
            [
                'numero' => 11,
                'cancha' => 1,
                'dia' => '',
                'horario' => '6° PRIMARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 11,
                'edad_fin' => 12,
                'baja' => '',
            ],
            [
                'numero' => 12,
                'cancha' => 1,
                'dia' => '',
                'horario' => '1° SECUNDARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 12,
                'edad_fin' => 13,
                'baja' => '',
            ],
            [
                'numero' => 13,
                'cancha' => 1,
                'dia' => '',
                'horario' => '2° SECUNDARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 13,
                'edad_fin' => 14,
                'baja' => '',
            ],
            [
                'numero' => 14,
                'cancha' => 1,
                'dia' => '',
                'horario' => '3° SECUNDARIA',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 14,
                'edad_fin' => 15,
                'baja' => '',
            ],
            [
                'numero' => 15,
                'cancha' => 1,
                'dia' => '',
                'horario' => 'DEUDOR',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 2,
                'edad_fin' => 12,
                'baja' => '',
            ],
            [
                'numero' => 16,
                'cancha' => 1,
                'dia' => 'LU/MA',
                'horario' => 'PREMATERNAL',
                'max_niños' => 30,
                'sexo' => 'MIXTO',
                'edad_ini' => 2,
                'edad_fin' => 2,
                'baja' => '',
            ],
        ];
        foreach ($registros as $registro) {
            DB::table('horarios')->updateOrInsert(
                [
                    'numero' => $registro['alumno'],
                ],
                $registro
            );
        }
    }
}
