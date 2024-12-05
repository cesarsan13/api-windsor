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
                'id' => 2,
                'name' => 'alfonso',
                'email' => 'alfonso@gmail.com',
                'numero_prop' => 1,
                'password' => Hash::make('123'),
            ],
            [
                'id' => 1,
                'nombre'=>'Bernando Fernandez Fernandez',
                'name' => 'Bernando',
                'email' => 'bff@inter-op.com.mx',
                'numero_prop' => 1,
                'password' => Hash::make('B150958m'),
                'es_admin' => true,
            ],
            [
                'id' => 3,
                'name' => 'martha',
                'email' => 'martha@gmail.com',
                'numero_prop' => 1,
                'password' => Hash::make('madt'),
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
