<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $escuela = $request->xEscuela ?? session("xEscuela");
        if (!$escuela) {
            return response()->json(["error" => "Escuela no Seleccionda"]);
        }
        try {
            DB::purge('dynamic');
            Config::set('database.default', 'mysql');
            DB::reconnect('mysql');
            $configuracion = BasesDatos::where('id', $escuela)->where('proyecto', 'control_escolar')->first();
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
                session(['xEscuela' => $escuela]);
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
