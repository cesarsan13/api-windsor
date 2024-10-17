<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ObjectResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Calificaciones;
use App\Models\Alumno;
use App\Models\Profesores;
use App\Models\Clases;
use App\Models\Materias;
use Illuminate\Support\Facades\Validator;

class ProcesosController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
        'numeric' => 'El campo :attribute debe ser un número decimal.',
        'string' => 'El campo :attribute debe ser una cadena.',
        'integer' => 'El campo :attribute debe ser un número.',
        'boolean' => 'El campo :attribute debe ser un valor booleano.',
    ];
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

    public function cancelarRecibo(Request $request)
    {
        // Log::info($request);
        $fecha = $request->fecha;
        $recibo = $request->recibo;
        $cobranza_diaria = DB::table('cobranza_diaria')
            ->where('recibo', '=', $recibo)
            ->where('fecha_cobro', '<>', $fecha)
            ->first();
        // Log::info($cobranza_diaria);
        if (!$cobranza_diaria) {
            $response = ObjectResponse::BadResponse();
            data_set($response, 'alert_title', 'Cancelación de Recibos');
            data_set($response, 'alert_text', 'Número de recibo no localizado');
            return response()->json($response, $response['status_code']);
        }
        $encab_pedido = DB::table('encab_pedido')
            ->where('recibo', '=', $recibo)
            ->first();
        // Log::info($encab_pedido);
        if (!$encab_pedido) {
            $response = ObjectResponse::BadResponse();
            data_set($response, 'alert_title', 'Cancelación de Recibos');
            data_set($response, 'alert_text', 'Número de recibo facturado');
            return response()->json($response, $response['status_code']);
        }
        $detalle_pedido = DB::table('detalle_pedido')
            ->where('recibo', '=', $recibo)
            ->first();
        if (!$detalle_pedido) {
            $response = ObjectResponse::BadResponse();
            data_set($response, 'alert_title', 'Cancelación de Recibos');
            data_set($response, 'alert_text', 'Númerode recibo no localizado');
            return response()->json($response, $response['status_code']);
        } else {
            if ($detalle_pedido->documento > 0) {
                DB::table('documentos_cobranza')
                    ->where('alumno', '=', $detalle_pedido->alumno)
                    ->where('producto', '=', $detalle_pedido->articulo)
                    ->where('numero_doc', '=', $detalle_pedido->documento)
                    ->update([
                        'importe_pago' => 0,
                        'fecha_cobros' => ' '
                    ]);
            }

            DB::table('detalle_pedido')
                ->where('recibo', '=', $recibo)
                ->update([
                    'cantidad' => 0,
                    'precio_unitario' => 0,
                    'descuento' => 0,
                    'iva' => 0,
                    'fecha' => ' '
                ]);

            db::table('encab_pedido')
                ->where('recibo', '=', $recibo)
                ->where('fecha', '=', $fecha)
                ->update([
                    'importe_total' => 0,
                    'tipo_pago_1' => 0,
                    'importe_pago_1' => 0,
                    'referencia_1' => ' ',
                    'tipo_pago_2' => 0,
                    'importe_pago_2' => 0,
                    'referencia_2' => ' ',
                ]);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'alert_title', 'Cancelación de Recibos');
            data_set($response, 'alert_text', 'Recibo cancelado correctamente');
            return response()->json($response, $response['status_code']);
        }
    }
    public function buscarCalificaciones(Request $request)
    {
        $rules = [
            // 'alumno' => 'required',
            'grupo' => 'required',
            'materia' => 'required',
            'bimestre' => 'required',
            'cb_actividad' => 'required|boolean',
            'actividad' => 'nullable',
            'unidad' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        // $alumno = $request->alumno;
        $grupo = $request->grupo;
        $materia = $request->materia;
        $cb_actividad = $request->cb_actividad;
        $actividad = $request->actividad;
        $unidad = $request->unidad;
        $bimestre = $request->bimestre;

        $alumnos = Alumno::select('numero', 'nombre')
            ->where('grupo', '=', $grupo)
            ->orderBy('nombre')
            ->get();

        if ($alumnos->isEmpty()) {
            $response = ObjectResponse::BadResponse('No se encontraron alumnos');
            return response()->json($response, $response['status_code']);
        }

        $response_data = [];
        foreach ($alumnos as $alumno) {
            $calificacion_query = Calificaciones::select('calificacion')
                ->where('alumno', '=', $alumno->numero)
                ->where('materia', '=', $materia)
                ->where('grupo', '=', $grupo)
                ->where('bimestre', '=', $bimestre);

            if ($cb_actividad === true) {
                $calificacion_query->where('actividad', '=', $actividad)
                    ->where('unidad', '=', $unidad);
            }

            $calificacion = $calificacion_query->first();

            $response_data[] = [
                'numero' => $alumno->numero,
                'nombre' => $alumno->nombre,
                'unidad' => $unidad ?? '',
                'calificacion' => $calificacion->calificacion ?? '',
            ];
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_title', 'Lista de calificaciones');
        data_set($response, 'alert_text', 'Lista de calificaciones');
        data_set($response, 'data', $response_data);

        return response()->json($response, $response['status_code']);
    }

    public function materiaBuscar(Request $request)
    {
        $rules = [
            'numero' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        $materias = Materias::select('descripcion', 'area', 'actividad', 'caso_evaluar')
            ->where('numero', '=', $request->numero)
            ->get();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $materias);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function materiaBuscarEvaluacion(Request $request)
    {
        $rules = [
            'numero' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        $materias = Materias::select('evaluaciones')
            ->where('numero', '=', $request->numero)
            ->get();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $materias);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function actividadesSecuencia(Request $request)
    {
        $rules = [
            'materia' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        $act = Actividad::select('secuencia as id', 'descripcion')
            ->where('materia', '=', $request->materia)
            ->where('baja', '<>', '*')
            ->get();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $act);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function indexBuscaCat(Request $request)
    {
        $rules = [
            'grupo' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $resultados = Clases::leftJoin('materias', 'clases.materia', '=', 'materias.numero')
            ->leftJoin('horarios', 'clases.grupo', '=', 'horarios.numero')
            ->select('clases.grupo', 'horarios.horario', 'clases.materia', 'materias.actividad', 'materias.descripcion', 'materias.area', 'materias.caso_evaluar')
            ->where('clases.grupo', $request->grupo)
            ->orderBy('materias.descripcion')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $resultados);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function getContraseñaProfe(Request $request)
    {
        $rules = [
            'grupo' => 'required',
            'materia' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $grupo = $request->grupo;
        $materia = $request->materia;
        $contraseña = Profesores::where('numero', function ($query) use ($grupo, $materia) {
            $query->select('profesor')
                ->from('clases')
                ->where('grupo', $grupo)
                ->where('materia', $materia)
                ->limit(1);
        })
            ->first();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $contraseña);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }
}
