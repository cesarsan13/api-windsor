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
        DB::table('propietario')->insert([
            'Numero' => 1,
            'Nombre' => 'COLEGIO BILINGUE WINDSOR',
            'Clave_Seguridad' => 'GKPIFG>@FE',
            'Busqueda_Max' => 313,
            'Inscripcion' => 0.00,
            'Con_Recibos' => 0,
            'Con_Facturas' => 0,
            'Clave_Bonificacion' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
