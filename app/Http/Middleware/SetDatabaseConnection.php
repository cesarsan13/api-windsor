<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $escuela = session("xEscuela") ?? $request->xEscuela;

        if (!$escuela) {
            return response()->json(["error" => "Escuela no Seleccionda"]);
        }
        $connections = config('database.connections');
        if (!isset($connections[$escuela])) {
            return response()->json(['error' => 'Configuración de la base de datos no encontrada'], 404);
        }
        $conexion =[];
        // Establece la conexión
        Config::set('database.default', $escuela);
        DB::purge(); // Limpia conexiones previas para evitar conflictos
        return $next($request);
    }
}
