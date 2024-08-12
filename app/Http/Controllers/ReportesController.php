<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use App\Models\Producto;
use App\Models\Cobranza_Diaria;
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

    public function getCobranzaAlumno(Request $request)
    {

        $tomaFecha = $request->input('tomafecha');
        $fecha_cobro_ini = $request->input('fecha_cobro_ini');
        $fecha_cobro_fin = $request->input('fecha_cobro_fin');
        $alumno_ini = $request->input('alumno_ini');
        $alumno_fin = $request->input('alumno_fin');
        $cajero_ini = $request->input('cajero_ini');
        $cajero_fin = $request->input('cajero_fin');

        $query = DB::table('detalle_pedido AS DP')
            ->select(
                'A.id AS id_al',
                'A.nombre AS nom_al',
                'DP.articulo',
                'PS.descripcion',
                'DC.numero_doc',
                'DP.fecha',
                DB::raw('round((DP.cantidad * DP.precio_unitario) - ((DP.cantidad * DP.precio_unitario) * (DP.descuento / 100) ), 2) AS importe'),
                'DP.recibo',
                'TC1.descripcion AS desc_Tipo_Pago_1',
                'TC2.descripcion AS desc_Tipo_Pago_2',
                'CS.nombre'
            )

            ->Join('productos AS PS', 'DP.articulo', '=', 'PS.id')
            ->Join('alumnos AS A', 'DP.alumno', '=', 'A.id')
            ->Join('cobranza_diaria AS CD', 'DP.recibo', '=', 'CD.recibo')
            ->Join('cajeros AS CS', 'CD.cajero', '=', 'CS.numero')
            ->Join('documentos_cobranza AS DC', 'DP.alumno', '=', 'DC.alumno')
            ->leftJoin(DB::raw('tipo_cobro AS TC1'), 'TC1.id', '=', 'CD.tipo_pago_1')
            ->leftJoin(DB::raw('tipo_cobro AS TC2'), 'TC2.id', '=', 'CD.tipo_pago_2')
            ->where('importe_cobro', '>', 0);

        if ($tomaFecha === true) {
            $query->whereBetween('CD.fecha_cobro', [$fecha_cobro_ini, $fecha_cobro_fin]);
        }

        if ($alumno_ini > 0 || $alumno_fin > 0) {
            if ($alumno_fin == 0) {
                $query->where('CD.alumno', '=', $alumno_ini);
            } else {
                $query->whereBetween('CD.alumno', [$alumno_ini, $alumno_fin]);
            }
        }

        if ($cajero_ini > 0 || $cajero_fin > 0) {
            if ($cajero_fin == 0) {
                $query->where('CD.cajero', '=', $cajero_ini);
            } else {
                $query->whereBetween('CD.cajero', [$cajero_ini, $cajero_fin]);
            }
        }

        $query->orderBy('id_al', 'ASC');
        $respuesta = $query->get();
        /*dd($query->toSql());*/

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Cobranza Alumnos');
        data_set($response, 'data', $respuesta);
        return response()->json($response, $response['status_code']);
    }

    public function getConsultasInscripcion()
    {
        $alumnos = DB::table('alumnos')->where('estatus', '=', 'activo')->orderBy('id', 'ASC')->get();
        $det_ped = DB::table('detalle_pedido')->get();
        $productos = DB::table('productos')->get();
        $horarios = DB::table('horarios')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Cobranza Alumnos');
        data_set($response, 'data_alumnos', $alumnos);
        data_set($response, 'data_detalle', $det_ped);
        data_set($response, 'data_productos', $productos);
        data_set($response, 'data_horarios', $horarios);
        return response()->json($response, $response['status_code']);
    }

    public function getRelaciondeFacturas (Request $request){

        $tomaFecha = $request->input('tomaFecha');
        $tomaCanceladas = $request->input('tomaCanceladas');
        $fecha_cobro_ini = $request->input('fecha_cobro_ini');
        $fecha_cobro_fin = $request->input('fecha_cobro_fin');
        $factura_ini = $request->input('factura_ini');
        $factura_fin = $request->input('factura_fin');

        $query = DB::table('detalle_pedido as DP')
        ->select('DP.numero_factura', 'DP.alumno', 'DP.recibo', 'DP.fecha', 'Al.razon_social', 'DP.iva', 'DP.descuento', 'DP.cantidad', 'DP.precio_unitario' )
        ->leftJoin('alumnos as Al', 'Al.id', '=', 'DP.alumno');
        
        if ($tomaCanceladas === true){
            
            if ($tomaFecha === true) {
                $query->whereBetween('DP.fecha', [$fecha_cobro_ini, $fecha_cobro_fin]); 
            }

            $query->where('DP.numero_factura','=', 0);

        } else {
            if ($tomaFecha === true) {
                $query->whereBetween('DP.fecha', [$fecha_cobro_ini, $fecha_cobro_fin])
                    ->where('DP.numero_factura','>', 0);
            }

            if ($factura_ini > 0 || $factura_fin > 0){
                if($factura_fin == 0){
                    $query->where('DP.numero_factura', '=', $factura_ini)
                    ->where('DP.numero_factura','>', 0);
                }else{
                    $query->whereBetween('DP.numero_factura', [$factura_ini, $factura_fin])
                    ->where('DP.numero_factura','>', 0);
                }
            }
        } 
        $query->orderBy('DP.numero_factura', 'ASC');
        $respuesta = $query->get();

        //$data = [$respuesta, $tomaFecha, $fecha_cobro_ini, $fecha_cobro_fin, $factura_ini, $factura_fin];

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria | Lista de Relacion de facturas');
        data_set($response, 'data', $respuesta);
        return response()->json($response, $response['status_code']);
    }
}

