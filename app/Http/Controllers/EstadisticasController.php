<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ObjectResponse;
use Illuminate\Support\Facades\Log;

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


    public function actualizarDocumentoCartera()
    {
        try {
            $cond_ant = 0;
            $productos = DB::table('productos')->where('baja', '<>', '*')->get();
            // Log::info($productos);
            foreach ($productos as $producto) {
                $existeDocumento = DB::table('documentos_cobranza')
                    ->where('ref', $producto->ref)
                    ->exists();
                if ($existeDocumento) {
                    Log::info("Documento con ref: {$producto->ref} ya existe.");
                    continue;
                }
                DB::table('documentos_cobranza')->updateOrInsert(
                    ['ref' => $producto->ref],
                    ['producto' => $producto->numero]
                );

                $cond_ant = $producto->numero;
            }

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $cond_ant);
            data_set($response, 'message', 'peticion satisfactoria');
            data_set($response, 'alert_title', 'EXITO!, Productos actualizados en documentos cobranza');
            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }

    public function procesoCartera(Request $request)
    {
        try {
            // $cond_ant = $request->cond_ant;
            $cond_ant = 0;
            $fecha = $request->fecha;
            $cond_1 = $request->cond_1;
            $periodo = $request->periodo;
            $productos = DB::table('productos')
                ->where('cond_1', '=', $cond_1)
                ->where('baja', '<>', '*')
                ->get();
            // Log::info($productos);
            foreach ($productos as $producto) {
                try {
                    if ($cond_ant != $producto->cond_1) {
                        DB::table('documentos_cobranza')
                            ->where('producto', '=', $producto->numero)
                            ->where('ref', '=', '')
                            ->update([
                                'ref' => $producto->ref,
                            ]);
                    }
                    $cond_ant = $producto->cond_1;
                    $alumnos = DB::table('alumnos')
                        ->where('cond_1', '=', $cond_1)
                        ->where('cond_2', '=', $cond_1)
                        ->where('baja', '<>', '*')
                        ->get();
                    // Log::info($alumnos);
                    foreach ($alumnos as $alumno) {
                        try {
                            if (strtolower($alumno->estatus) === 'activo') {
                                $documentoExistente = DB::table('documentos_cobranza')
                                    ->where('alumno', '=', $alumno->numero)
                                    ->where('producto', '=', $producto->numero)
                                    ->where('numero_doc', '=', $periodo)
                                    ->first();
                                // Log::info($documentoExistente);
                                if (!$documentoExistente) {
                                    $insertdoc = DB::table('documentos_cobranza')->insert([
                                        'alumno' => $alumno->numero,
                                        'producto' => $producto->numero,
                                        'numero_doc' => $periodo,
                                        'fecha' => $fecha,
                                        'ref' => $producto->ref,
                                        'descuento' => $alumno->descuento,
                                        'importe' => $producto->costo,
                                    ]);
                                    // Log::info($insertdoc);
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Error procesando alumno: ' . $alumno->numero . ' - ' . $e->getMessage());
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error procesando producto: ' . $producto->numero . ' - ' . $e->getMessage());
                    continue;
                }
            }
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria');
            data_set($response, 'alert_title', '¡ÉXITO! Cartera procesada');
            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
}
