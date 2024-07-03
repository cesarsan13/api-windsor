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


        
            $registros=[
                [
                'id' => 1,
                'name' => 'alfonso',
                'email' => 'alfonso@gmail.com',
                'numero_prop'=> 1,
                'password'=>Hash::make('123'),
                ],
            [
                'id' => 2,
                'name' => 'martha',
                'email' => 'martha@gmail.com',
                'numero_prop'=> 1,
                'password'=>Hash::make('madt'),
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
