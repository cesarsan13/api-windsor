<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\ObjectResponse;

class EstructurasController extends Controller
{
    public function runMigrations(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $configuracion = $request->baseSeleccionada;
    
        try {
            $this->setDatabase($configuracion, null);
            DB::connection("dynamic")->statement("CREATE DATABASE IF NOT EXISTS `$configuracion[database]`");
            set_time_limit(0);
            $this->setDatabase($configuracion, $configuracion["database"]);
    
            // **Eliminar todas las tablas y volver a crearlas**
            Artisan::call('migrate:fresh', [
                '--database' => "dynamic",
                '--force' => true,
            ]);
    
            // **Ejecutar todos los seeders**
            Artisan::call('db:seed', [
                '--database' => 'dynamic',
                '--force' => true, 
            ]);
    
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Migraciones y seeders ejecutados correctamente');
            return response()->json($response, $response['status_code']);
    
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    
    
    public function setDatabase($configuracion, $database)
    {
        DB::purge('dynamic');
        Config::set("database.connections.dynamic", [
            'driver' => 'mysql',
            'host' => $configuracion["host"],
            'port' => $configuracion["port"],
            'database' => $database,
            'username' => $configuracion["username"],
            'password' => $configuracion["password"],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]);
        Config::set('database.default', 'dynamic');
        DB::reconnect('dynamic');
    }
}
