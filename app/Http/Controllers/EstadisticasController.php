<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ObjectResponse;

class EstadisticasController extends Controller
{
    public function obtenerEstadisticas()
    {
        $totalAlumnos = DB::table('alumnos')
            ->where('baja', '<>', '*')
            ->count('numero');
        $totalCursos = DB::table('horarios')
            ->where('baja', '<>', '*')
            ->count('numero');
        $promedioAlumnosPorCurso = DB::table('alumnos as a')
            ->join('horarios as h', 'a.horario_1', '=', 'h.numero')
            ->select('h.horario AS grado', DB::raw('COUNT(a.numero) AS total_estudiantes'))
            ->groupBy('h.horario', 'h.numero')
            ->get();
        $horarioMasAlumnos = DB::table('alumnos as a')
            ->join('horarios as h', 'a.horario_1', '=', 'h.numero')
            ->select('h.horario', DB::raw('COUNT(a.numero) AS total_estudiantes'))
            ->groupBy('h.horario')
            ->orderBy('total_estudiantes', 'DESC')
            ->limit(1)
            ->first();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'total_alumnos', $totalAlumnos);
        data_set($response, 'total_cursos', $totalCursos);
        data_set($response, 'promedio_alumnos_por_curso', $promedioAlumnosPorCurso);
        data_set($response, 'horarios_populares', $horarioMasAlumnos);
        return response()->json($response, $response['status_code']);
    }

    public function mesActualCajeros()
    {
        $response = ObjectResponse::DefaultResponse();
        $queryCajero = DB::table('cobranza_diaria')
            ->join('cajeros', 'cobranza_diaria.cajero', '=', 'cajeros.numero')
            ->whereMonth('fecha_cobro', date('m'))
            ->select('cobranza_diaria.*', 'cajeros.nombre');
        $resultCajero = $queryCajero->orderBy('cajero')->get();

        $queryProducto = DB::table('detalle_pedido')
            ->join('productos', 'detalle_pedido.articulo', '=', 'productos.numero')
            ->whereMonth('detalle_pedido.fecha', date('m'))
            ->orderBy('detalle_pedido.articulo')
            ->select('detalle_pedido.*', 'productos.descripcion');
        $resultProducto = $queryProducto->get();

        $queryTipoPago = DB::table('cobranza_diaria')
            ->join('tipo_cobro AS tipo_pago_1', 'cobranza_diaria.tipo_pago_1', '=', 'tipo_pago_1.numero')
            ->leftJoin('tipo_cobro AS tipo_pago_2', 'cobranza_diaria.tipo_pago_2', '=', 'tipo_pago_2.numero')
            ->whereMonth('cobranza_diaria.fecha_cobro', date('m'))
            ->select('cobranza_diaria.*', 'tipo_pago_1.descripcion AS descripcion1', 'tipo_pago_2.descripcion AS descripcion2');
        $resultTipoPago = $queryTipoPago->get();

        $data = [
            "cajeros" => $resultCajero,
            "producto" => $resultProducto,
            "tipo_pago" => $resultTipoPago,
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de mes actual por cajeros');
        data_set($response, 'data', $data);
        return response()->json($response, $response['status_code']);
    }
}
