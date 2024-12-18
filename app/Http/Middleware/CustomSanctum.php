<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Guard;
use Laravel\Sanctum\PersonalAccessToken;
class CustomSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Validar el token utilizando Sanctum
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Asociar el usuario autenticado al request
        $request->setUserResolver(function () use ($accessToken) {
            return $accessToken->tokenable;
        });

        // // Agregar lógica personalizada (opcional)
        // if ($accessToken->abilities && !in_array('access-api', $accessToken->abilities)) {
        //     return response()->json(['message' => 'Insufficient permissions'], 403);
        // }

        // Continuar con la solicitud
        return $next($request);
    }
}
