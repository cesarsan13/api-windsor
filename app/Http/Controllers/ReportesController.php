<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use App\Models\Producto;
use App\Models\Encab_Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;

class ReportesController extends Controller
{
    public function getAlumnosPorClaseSemanal(Request $request)
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $horario = Horario::where('numero', '=', $request->horario)->get();
            $idHorario = $request->horario;
            $alumnosHorario1 = Alumno::where('baja', '<>', '*')
                ->where(function ($query) use ($idHorario) {
                    $query->orWhere("horario_1", "=", $idHorario)
                        ->orWhere("horario_2", "=", $idHorario)
                        ->orWhere("horario_3", "=", $idHorario)
                        ->orWhere("horario_4", "=", $idHorario)
                        ->orWhere("horario_5", "=", $idHorario)
                        ->orWhere("horario_6", "=", $idHorario)
                        ->orWhere("horario_7", "=", $idHorario)
                        ->orWhere("horario_8", "=", $idHorario)
                        ->orWhere("horario_9", "=", $idHorario)
                        ->orWhere("horario_10", "=", $idHorario)
                        ->orWhere("horario_11", "=", $idHorario)
                        ->orWhere("horario_12", "=", $idHorario)
                        ->orWhere("horario_13", "=", $idHorario)
                        ->orWhere("horario_14", "=", $idHorario)
                        ->orWhere("horario_15", "=", $idHorario)
                        ->orWhere("horario_16", "=", $idHorario)
                        ->orWhere("horario_17", "=", $idHorario)
                        ->orWhere("horario_18", "=", $idHorario)
                        ->orWhere("horario_19", "=", $idHorario)
                        ->orWhere("horario_20", "=", $idHorario);
                })
                ->orderBy($request->orden, 'ASC')->get(['id', 'nombre', 'fecha_nac', 'telefono_1']);

            $rep_dos_sel = ObjectResponse::Rep_Dos_Sel(32);
            $rep_dos_sel = ObjectResponse::PrepHorario($alumnosHorario1, $rep_dos_sel, 1);
            $reporte = [
                "horario" => $horario,
                "data" => $rep_dos_sel,
            ];
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de tipos de cobro');
            data_set($response, 'data', $reporte);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    //Alumnos por clase
    public function getAlumnosPorClase(Request $request)
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $horario = Horario::where('numero', '=', $request->horario1)->get();
            $idHorario = $request->horario1;
            $alumnosHorario1 = Alumno::where('baja', '<>', '*')
                ->where(function ($query) use ($idHorario) {
                    $query->orWhere("horario_1", "=", $idHorario)
                        ->orWhere("horario_2", "=", $idHorario)
                        ->orWhere("horario_3", "=", $idHorario)
                        ->orWhere("horario_4", "=", $idHorario)
                        ->orWhere("horario_5", "=", $idHorario)
                        ->orWhere("horario_6", "=", $idHorario)
                        ->orWhere("horario_7", "=", $idHorario)
                        ->orWhere("horario_8", "=", $idHorario)
                        ->orWhere("horario_9", "=", $idHorario)
                        ->orWhere("horario_10", "=", $idHorario)
                        ->orWhere("horario_11", "=", $idHorario)
                        ->orWhere("horario_12", "=", $idHorario)
                        ->orWhere("horario_13", "=", $idHorario)
                        ->orWhere("horario_14", "=", $idHorario)
                        ->orWhere("horario_15", "=", $idHorario)
                        ->orWhere("horario_16", "=", $idHorario)
                        ->orWhere("horario_17", "=", $idHorario)
                        ->orWhere("horario_18", "=", $idHorario)
                        ->orWhere("horario_19", "=", $idHorario)
                        ->orWhere("horario_20", "=", $idHorario);
                })
                ->orderBy($request->orden, 'ASC')->get(['id', 'nombre', 'fecha_nac', 'telefono_1']);
            $idHorario2 = $request->horario2;
            $alumnosHorario2 = Alumno::where('baja', '<>', '*')
                ->where(function ($query) use ($idHorario2) {
                    $query->orWhere("horario_1", "=", $idHorario2)
                        ->orWhere("horario_2", "=", $idHorario2)
                        ->orWhere("horario_3", "=", $idHorario2)
                        ->orWhere("horario_4", "=", $idHorario2)
                        ->orWhere("horario_5", "=", $idHorario2)
                        ->orWhere("horario_6", "=", $idHorario2)
                        ->orWhere("horario_7", "=", $idHorario2)
                        ->orWhere("horario_8", "=", $idHorario2)
                        ->orWhere("horario_9", "=", $idHorario2)
                        ->orWhere("horario_10", "=", $idHorario2)
                        ->orWhere("horario_11", "=", $idHorario2)
                        ->orWhere("horario_12", "=", $idHorario2)
                        ->orWhere("horario_13", "=", $idHorario2)
                        ->orWhere("horario_14", "=", $idHorario2)
                        ->orWhere("horario_15", "=", $idHorario2)
                        ->orWhere("horario_16", "=", $idHorario2)
                        ->orWhere("horario_17", "=", $idHorario2)
                        ->orWhere("horario_18", "=", $idHorario2)
                        ->orWhere("horario_19", "=", $idHorario2)
                        ->orWhere("horario_20", "=", $idHorario2);
                })
                ->orderBy($request->orden, 'ASC')->get(['id', 'nombre', 'fecha_nac', 'telefono_1']);
            $rep_dos_sel = ObjectResponse::Rep_Dos_Sel(32);
            $rep_dos_sel = ObjectResponse::PrepHorario($alumnosHorario1, $rep_dos_sel, 1);
            $rep_dos_sel = ObjectResponse::PrepHorario($alumnosHorario2, $rep_dos_sel, 2);
            $reporte = [
                "horario" => $horario,
                "data" => $rep_dos_sel,
            ];
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de tipos de cobro');
            data_set($response, 'data', $reporte);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function getAlumnosPorMes(Request $request)
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $horario = Horario::where('numero', '=', $request->horario)->get();
            $idHorario = $request->horario;
            $alumnosHorario = Alumno::where('baja', '<>', '*')
                ->where(function ($query) use ($idHorario) {
                    $query->orWhere("horario_1", "=", $idHorario)
                        ->orWhere("horario_2", "=", $idHorario)
                        ->orWhere("horario_3", "=", $idHorario)
                        ->orWhere("horario_4", "=", $idHorario)
                        ->orWhere("horario_5", "=", $idHorario)
                        ->orWhere("horario_6", "=", $idHorario)
                        ->orWhere("horario_7", "=", $idHorario)
                        ->orWhere("horario_8", "=", $idHorario)
                        ->orWhere("horario_9", "=", $idHorario)
                        ->orWhere("horario_10", "=", $idHorario)
                        ->orWhere("horario_11", "=", $idHorario)
                        ->orWhere("horario_12", "=", $idHorario)
                        ->orWhere("horario_13", "=", $idHorario)
                        ->orWhere("horario_14", "=", $idHorario)
                        ->orWhere("horario_15", "=", $idHorario)
                        ->orWhere("horario_16", "=", $idHorario)
                        ->orWhere("horario_17", "=", $idHorario)
                        ->orWhere("horario_18", "=", $idHorario)
                        ->orWhere("horario_19", "=", $idHorario)
                        ->orWhere("horario_20", "=", $idHorario);
                })
                ->orderBy($request->orden, 'ASC')->get(['id', 'nombre', 'fecha_nac']);
            $rep_dos_sel = ObjectResponse::Rep_Dos_Sel(32);
            $rep_dos_sel = ObjectResponse::PrepHorario($alumnosHorario, $rep_dos_sel, 1);
            $reporte = [
                "horario" => $horario,
                "data" => $rep_dos_sel,
            ];
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de tipos de cobro');
            data_set($response, 'data', $reporte);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function getRelaciondeRecibos(Request $request)
    {
        $tomaFecha = $request->input('tomaFecha');
        $fecha_ini = $request->input('fecha_ini');
        $fecha_fin = $request->input('fecha_fin');
        $factura_ini = $request->input('factura_ini');
        $factura_fin = $request->input('factura_fin');
        $recibo_ini = $request->input('recibo_ini');
        $recibo_fin = $request->input('recibo_fin');
        $alumno_ini = $request->input('alumno_ini');
        $alumno_fin = $request->input('alumno_fin');

        $query = DB::table('encab_pedidos as ep')
            ->leftJoin('alumnos as al', 'ep.alumno', 'al.id')
            ->leftJoin('cajeros as cj', 'ep.cajero', 'cj.numero')
            ->select(
                'ep.recibo',
                'ep.fecha',
                'ep.alumno',
                DB::raw("CONCAT(al.a_nombre, ' ', al.a_paterno, ' ', al.a_materno) as nombre_alumno"),
                'ep.cajero',
                'cj.nombre as nombre_cajero',
                'ep.importe_total',
                'ep.tipo_pago_1',
                'ep.importe_pago_1',
                'ep.referencia_1',
                'ep.tipo_pago_2',
                'ep.importe_pago_2',
                'ep.referencia_2',
                'ep.nombre_quien',
                'ep.comentario',
                'ep.comentario_ad',
                'ep.facturado',
                'ep.numero_factura',
                'ep.fecha_factura',
            );

        if ($tomaFecha === true) {
            $query->whereBetween('ep.fecha', [$fecha_ini, $fecha_fin]);
        }

        if ($recibo_ini > 0 || $recibo_fin > 0) {
            if ($recibo_fin == 0) {
                $query->where('ep.recibo', '=', $recibo_ini);
            } else {
                $query->whereBetween('ep.recibo', [$recibo_ini, $recibo_fin]);
            }
        }

        if ($factura_ini > 0 || $factura_fin > 0) {
            if ($factura_fin == 0) {
                $query->where('ep.Numero_Factura', '=', $factura_ini);
            } else {
                $query->whereBetween('ep.Numero_Factura', [$factura_ini, $factura_fin]);
            }
        }

        if ($alumno_ini > 0 || $alumno_fin > 0) {
            if ($alumno_fin == 0) {
                $query->where('ep.alumno', '=', $alumno_ini);
            } else {
                $query->whereBetween('ep.alumno', [$alumno_ini, $alumno_fin]);
            }
        }

        $query->orderBy('ep.recibo', 'ASC');
        $resultados = $query->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $resultados);
        return response()->json($response, $response['status_code']);
    }
    public function getBecas(Request $request) {
        $response = ObjectResponse::DefaultResponse();
        $incBaja = $request->input('incBaja', 0);
        $socio = $request->input('socio', [0, 0]);
        $horarioFiltro = $request->input('horario', null);
    
        $query = Alumno::where('id', '<>', '')
            ->where('estatus', 'Activo')
            ->where('descuento', '>', 0);
    
        if ($incBaja == 1) {
            $query->where('baja', '<>', '*');
        }
    
        if ((int)$socio[0] > 0 || (int)$socio[1] > 0) {
            if ((int)$socio[1] === 0) {
                $query->where('id', (int)$socio[0]);
            } else {
                $query->whereBetween('id', [(int)$socio[0], (int)$socio[1]]);
            }
        }
    
        if ($horarioFiltro !== null) {
            $query->where('horario_1', $horarioFiltro);
        }
    
        $alumnos = $query->orderBy('descuento', 'DESC')->get();
    
        $resultados = []; 
        foreach ($alumnos as $alumno) {
            $horario = Horario::where('numero', $alumno->horario_1)->first();
            $producto = Producto::where('id', $alumno->cond_1)->first();
            $costoProd = $producto ? $producto->costo : 0;
    
            $twDescuento = $costoProd * ($alumno->descuento / 100);
            $costoFinal = $costoProd - $twDescuento;
            
            $resultados[] = [
                'numero' => $alumno->id,
                'alumno' => $alumno->nombre,
                'grado' => $horario ? $horario->horario : null,
                'colegiatura' => $costoProd,
                'descuento' => $twDescuento,
                'costo_final' => $costoFinal,
            ];
        }
    
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $resultados); 
    
        return response()->json($response, $response['status_code']);
    }
}
    
