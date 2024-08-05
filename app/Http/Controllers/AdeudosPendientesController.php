<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdeudosPendientesController extends Controller
{
    public function getDetallePedidos(Request $request) {
        $fecha_ini = $request->input('fecha_ini');
        $fecha_fin = $request->input('fecha_fin');

        $query = DB::table('documentos_cobranza')
        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])
        ->orderBy('fecha');
        $resultados = $query->get();

        $queryAlumnos = DB::table('alumnos')
        ->select('estatus','id');
        $resultadosAlumnos = $queryAlumnos->get();
        $data = [
            'documentos_cobranza'=>$resultados,
            'alumnos'=>$resultadosAlumnos
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $data);
        return response()->json($response, $response['status_code']);
    }
}
