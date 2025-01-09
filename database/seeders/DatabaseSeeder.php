<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            punto_menuSeeder::class,
            AccesosMenuSeeder::class,
            AccesoUsuarioSeeder::class,
            TipoCobroSeeder::class,
            ReferenciaSeeder::class,
            PropietarioSeeder::class,
            FacturasFormatoSeeder::class,
            // Agrega más seeders si los tienes
        ]);
        // User::factory(10)->create();


        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
