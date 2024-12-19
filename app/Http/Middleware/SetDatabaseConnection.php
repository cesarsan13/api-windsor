<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\BasesDatos;
class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $escuela = (int) $request->xescuela === 0 ? (int) $request->header('xescuela') : (int) $request->xescuela;
        if (!$escuela) {
            return response()->json(["error" => "Escuela no Seleccionduca"]);
        }
        try {
            DB::purge('dynamic');
            Config::set('database.default', 'mysql');
            DB::reconnect('mysql');
            $apiUrl = env("API_PROYECTOS_URL");
            $response = Http::get($apiUrl . "api/basesDatos/{$escuela}/control_escolar");
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'][0])) {
                    $configuracion = (object) $data['data'][0]; // Convierte el primer elemento a un objeto
                } else {
                    return response()->json(["error" => "Datos de configuración no encontrados"], 500);
                }
            } else {
                Log::error('Error al llamar a la API 8001', ['status' => $response->status()]);
            }
            if ($configuracion) {
                DB::purge('dynamic');
                Config::set("database.connections.dynamic", [
                    'driver' => 'mysql',
                    'host' => $configuracion->host,
                    'port' => $configuracion->port,
                    'database' => $configuracion->database,
                    'username' => $configuracion->username,
                    'password' => $configuracion->password,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]);
                Config::set('database.default', 'dynamic');
                DB::reconnect('dynamic');
            }
        } catch (\Exception $ex) {
            return response()->json([
                "error" => "Error al configurar la conexión a la base de datos",
                "message" => $ex->getMessage()
            ], 500);
        }
        return $next($request);
    }
}
