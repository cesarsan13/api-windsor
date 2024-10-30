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

    public function buscarCalificaciones_1(Request $request)
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

        $grupo = $request->grupo;
        $alumnos = Alumno::select('numero', 'nombre')
            ->where('grupo', '=', $grupo)
            ->orderBy('nombre')
            ->get();

        if ($alumnos->isEmpty()) {
            $response = ObjectResponse::BadResponse('No se encontraron alumnos');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_title', 'Lista de alumnos por grupo');
        data_set($response, 'alert_text', 'Lista de alumnos por grupo');
        data_set($response, 'data', $alumnos);
        return response()->json($response, $response['status_code']);
    }
    public function buscarCalificaciones_2(Request $request)
    {
        try {
            $rules = [
                // 'alumno' => 'required',
                'numero' => 'required',
                'nombre' => 'required',
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

            $numero = $request->numero;
            $nombre = $request->nombre;
            $grupo = $request->grupo;
            $materia = $request->materia;
            $cb_actividad = $request->cb_actividad;
            $actividad = $request->actividad;
            $unidad = $request->unidad;
            $bimestre = $request->bimestre;

            if ($cb_actividad === true) {
                $calificacion = Calificaciones::select('calificacion')
                    ->where('alumno', '=', $numero)
                    ->where('materia', '=', $materia)
                    ->where('grupo', '=', $grupo)
                    ->where('bimestre', '=', $bimestre)
                    ->where('actividad', '=', $actividad)
                    ->where('unidad', '=', $unidad)
                    ->first();
            } else {
                $calificacion = Calificaciones::select('calificacion')
                    ->where('alumno', '=', $numero)
                    ->where('materia', '=', $materia)
                    ->where('grupo', '=', $grupo)
                    ->where('bimestre', '=', $bimestre)
                    ->first();
            }
            Log::info($calificacion);
            $response_data[] = [
                'numero' => $numero,
                'nombre' => $nombre,
                'unidad' => $unidad ?? '',
                'calificacion' => $calificacion->calificacion ?? '',
            ];
            // }

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'alert_title', 'Lista de calificaciones');
            data_set($response, 'alert_text', 'Lista de calificaciones');
            data_set($response, 'data', $response_data);
            data_set($response, 'calis', $calificacion);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }

    public function buscaTareasTrabajosPendientes(Request $request){
        try {
            
            $rules = [
                // 'alumno' => 'required',
                'numero' => 'required',
                'nombre' => 'required',
                'grupo' => 'required',
                'materia' => 'required',
                'bimestre' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = ObjectResponse::BadResponse('Error de validación');
                data_set($response, 'errors', $validator->errors());
                return response()->json($response, $response['status_code']);
            }
            $numero = $request->numero;
            $nombre = $request->nombre;
            $grupo = $request->grupo;
            $materia = $request->materia;
            $bimestre = $request->bimestre;
            $calificacion = Calificaciones::select('calificacion')
                    ->where('alumno', '=', $numero)
                    ->where('materia', '=', $materia)
                    ->where('grupo', '=', $grupo)
                    ->where('bimestre', '=', $bimestre)
                    ->where('actividad', '=', 0)
                    ->where('unidad', '=', 0)
                    ->first();
            $sqlQuery = Calificaciones::select('calificacion')
                ->where('alumno', '=', $numero)
                ->where('materia', '=', $materia)
                ->where('grupo', '=', $grupo)
                ->where('bimestre', '=', $bimestre)
                ->where('actividad', '=', 0)
                ->where('unidad', '=', 0)
                ->toSql();
                $response_data[] = [
                    'numero' => $numero,
                    'nombre' => $nombre,
                    'unidad' => $unidad ?? '',
                    'calificacion' => $calificacion->calificacion ?? 0,
                    'materia' => $materia ?? 0
                ];
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'alert_title', 'Lista de calificaciones');
            data_set($response, 'alert_text', 'Lista de calificaciones. => '.$sqlQuery. " => ".$numero.";".$materia.";".$grupo.";".$bimestre);
            data_set($response, 'data', $response_data);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            return response()->json($response, $response['status_code']);
        }

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
    public function guardarC_Otras(Request $request){
        $rules = [
            'alumno' => 'required',
            'calificacion' => 'required',
            'materia' => 'required',
            'grupo' => 'required',
            'bimestre' => 'required',
            // 'actividad' => 'required',
            // 'unidad' => 'required',
        ];
        try {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $response = ObjectResponse::BadResponse('Error de validación');
                data_set($response, 'errors', $validator->errors());
                return response()->json($response, $response['status_code']);
            }
            $existe = DB::table('calificaciones')
                ->where('alumno', $request->alumno)
                ->where('grupo', $request->grupo)
                ->where('materia', $request->materia)
                ->where('bimestre', $request->bimestre)
                ->where('actividad', 0)
                ->where('unidad', 0)
                ->first();
            if (!$existe) {
                $calificacion = DB::table('calificaciones')->insert([
                    'alumno' => $request->alumno,
                    'calificacion' => $request->calificacion,
                    'materia' => $request->materia,
                    'grupo' => $request->grupo,
                    'bimestre' => $request->bimestre,
                    'actividad' => 0,
                    'unidad' => 0
                ]);
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'alert_text', "Calificación guardada con éxito");
                data_set($response, 'message', 'Calificación guardada con éxito');
            } else {
                DB::table('calificaciones')
                    ->where('alumno', $request->alumno)
                    ->where('grupo', $request->grupo)
                    ->where('materia', $request->materia)
                    ->where('bimestre', $request->bimestre)
                    ->where('actividad', 0)
                    ->where('unidad', 0)
                    ->update(['calificacion' => $request->calificacion]);
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'alert_text', "Calificación actualizada con éxito");
                data_set($response, 'message', 'Calificación actualizada con éxito');
            }

            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            return response()->json($response, $response['status_code']);
        }
        
        

    }

    public function guardarCalificaciones(Request $request)
    {
        $rules = [
            'alumno' => 'required',
            'calificacion' => 'required',
            'materia' => 'required',
            'grupo' => 'required',
            'bimestre' => 'required',
            'actividad' => 'required',
            'unidad' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $existe = DB::table('calificaciones')
            ->where('alumno', $request->alumno)
            ->where('grupo', $request->grupo)
            ->where('materia', $request->materia)
            ->where('bimestre', $request->bimestre)
            ->where('actividad', $request->actividad)
            ->where('unidad', $request->unidad)
            ->first();
        if (!$existe) {
            $calificacion = DB::table('calificaciones')->insert([
                'alumno' => $request->alumno,
                'calificacion' => $request->calificacion,
                'materia' => $request->materia,
                'grupo' => $request->grupo,
                'bimestre' => $request->bimestre,
                'actividad' => $request->actividad,
                'unidad' => $request->unidad
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'alert_text', "Calificación guardada con éxito");
            data_set($response, 'message', 'Calificación guardada con éxito');
        } else {
            DB::table('calificaciones')
                ->where('alumno', $request->alumno)
                ->where('grupo', $request->grupo)
                ->where('materia', $request->materia)
                ->where('bimestre', $request->bimestre)
                ->where('actividad', $request->actividad)
                ->where('unidad', $request->unidad)
                ->update(['calificacion' => $request->calificacion]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'alert_text', "Calificación actualizada con éxito");
            data_set($response, 'message', 'Calificación actualizada con éxito');
        }

        return response()->json($response, $response['status_code']);
    }


    public function buscarBoleta3(Request $request)
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
        $boleta = Clases::select('materias.numero', 'materias.descripcion', 'materias.area', 'materias.caso_evaluar')
            ->leftJoin('materias', 'clases.materia', '=', 'materias.numero')
            ->where('clases.baja', '<>', '*')
            ->where('materias.baja', '<>', '*')
            ->where('clases.grupo', $request->grupo)
            ->orderBy('materias.area')
            ->orderBy('materias.orden')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $boleta);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function buscarActividadMateria(Request $request)
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
        $boleta = Materias::select('actividad')
            ->where('numero', '=', $request->numero)
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $boleta);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function sacarEvaluacionMateria(Request $request)
    {
        $rules = [
            'grupo' => 'required',
            'bimestre' => 'required',
            'alumno' => 'required',
            'materia' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $materias = Materias::select('evaluaciones')
            ->where('numero', '=', $request->materia)
            ->first();
        // Log::info($materias);
        $calificacion = Calificaciones::where('grupo', '=', $request->grupo)
            ->where('bimestre', '=', $request->bimestre)
            ->where('alumno', '=', $request->alumno)
            ->where('actividad', '=', '0')
            ->where('materia', '=', $request->materia)
            ->where('unidad', '<=', $materias->evaluaciones)
            ->sum('calificacion');
        // ->first();
        if ($calificacion) {
            $resultados = $calificacion;
        } else {
            $resultados = 0;
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $resultados);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function buscarAreas1Y2(Request $request)
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
        $materias = Actividad::select('Secuencia', 'EB1', 'EB2', 'EB3', 'EB4', 'EB5')
            ->where('materia', '=', $request->materia)
            ->first();
        $sumatoria = 0;
        $evaluaciones = 0;
        foreach ($materias as $materia) {
            $cpa = Calificaciones::where('alumno', '=', $request->alumno)
                ->where('materia', '=', $request->materia)
                ->where('Bimestre', '=', $request->bimestre - 1)
                ->where('Actividad', '=', $materia->Secuencia)
                ->where('unidad', '<=', $materia->{'EB' . ($request->bimestre - 1)})
                ->sum('Calificacion');
            if ($cpa > 0) {
                $sumatoria += $this->truncarUno($cpa / $materia->{'EB' . ($request->bimestre - 1)});
                $evaluaciones += 1;
            }
        }
        if ($sumatoria === 0 || $evaluaciones === 0) {
            $cal = 0;
            $data = [
                "calificacion" => $cal
            ];
        } else {
            $calificacion = ($sumatoria / $evaluaciones);
            if ($calificacion < 5.0) {
                $calificacion = 5;
                $data = [
                    "calificacion" => $calificacion
                ];
            } else {
                $calificacion = ($sumatoria / $evaluaciones);
                $data = [
                    "calificacion" => $calificacion
                ];
            }
        }
        $response = ObjectResponse::CorrectResponse();
        // data_set($response, 'data', $);  
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function buscarAreasOtros(Request $request)
    {
        $rules = [
            'grupo' => 'required',
            'bimestre' => 'required',
            'alumno' => 'required',
            'materia' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $cali = Calificaciones::where('grupo', $request->grupo)
            ->where('alumno', $request->alumno)
            ->where('materia', $request->materia)
            ->where('bimestre', '<=', $request->bimestre)
            ->sum('calificacion');
        $suma = 0;
        if (!$cali) {
            $suma = 0;
        } else {
            $suma = $cali;
        }
        $data = [
            "calificacion" => $suma
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $data);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function truncarUno($numero)
    {
        return floor($numero * 10) / 10;
    }
}
