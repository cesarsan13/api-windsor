<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $registros = [

            [
                'id' => 1,
                'nombre' => 'Bernando Fernandez Fernandez',
                'name' => 'Bernando',
                'email' => 'bff@inter-op.com.mx',
                'numero_prop' => 1,
                'password' => Hash::make('B150958m'),
                'es_admin' => true,
            ],


        ];
        foreach ($registros as $registro) {
            DB::table('users')->updateOrInsert(
                ['id' => $registro['id']], // Condiciones para encontrar el registro
                $registro // Datos que se actualizarán o insertarán
            );
        }
    }
}
