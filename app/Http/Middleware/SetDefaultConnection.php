<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BasesDatos;
class SetDefaultConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::purge('dynamic');
            Config::set('database.default', 'mysql');
            DB::reconnect('mysql');
        } catch (\Exception $ex) {
            return response()->json([
                "error" => "Error al configurar la conexión a la base de datos",
                "message" => $ex->getMessage()
            ], 500);
        }
        return $next($request);
    }
}
