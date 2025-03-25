<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropietarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = [
            [

                'numero' => 1,
                'nombre' => 'COLEGIO BILINGUE WINDSOR',
                'clave_seguridad' => 'GKPIFG>@FE',
                'busqueda_max' => 313,
                'inscripcion' => 0.00,
                'con_recibos' => 0,
                'con_facturas' => 0,
                'clave_bonificacion' => '1',
            ],
        ];
        foreach ($registros as $registro) {
            DB::table('propietario')->updateOrInsert(
                ['numero' => $registro['numero']],
                $registro 
            );
        }
    }
}
