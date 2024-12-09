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
use Illuminate\Support\Facades\Log;

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
                ->orderBy($request->orden, 'ASC')->get(['numero', 'nombre', 'fecha_nac', 'telefono1']);

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
                ->orderBy($request->orden, 'ASC')->get(['numero', 'nombre', 'fecha_nac', 'telefono1']);
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
                ->orderBy($request->orden, 'ASC')->get(['numero', 'nombre', 'fecha_nac', 'telefono1']);
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
                ->orderBy($request->orden, 'ASC')->get(['numero', 'nombre', 'fecha_nac']);
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

        $query = DB::table('encab_pedido as ep')
            ->leftJoin('alumnos as al', 'ep.alumno', 'al.numero')
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
    public function getBecas(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $query = Alumno::select('*')->where('estatus','Activo')->where('descuento','>','0');
        if ((int)$request->alumno1 || (int)$request->alumno2) {
            if ((int)$request->alumno2 === 0) {                
                $query->where('numero', (int)$request->alumno1);
            } else {
                $query->whereBetween('numero', [(int)$request->alumno1, (int)$request->alumno2]);
            }
        }
        $alumnos = $query->orderBy('descuento', 'DESC')->get();        
        $resultados = [];
        foreach ($alumnos as $alumno) {
            $horario = Horario::where('numero', $alumno->horario_1)->first();
            $producto = Producto::where('numero', $alumno->cond_1)->first();
            $costoProd = $producto ? $producto->costo : 0;

            $twDescuento = $costoProd * ($alumno->descuento / 100);
            $costoFinal = $costoProd - $twDescuento;

            $resultados[] = [
                'numero' => $alumno->numero,
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
                'A.numero AS id_al',
                'A.nombre AS nom_al',
                'DP.articulo',
                'PS.descripcion',
                DB::raw("COALESCE(DC.numero_doc, '') AS numero_doc"),
                'DP.fecha',
                DB::raw('round((DP.cantidad * DP.precio_unitario) - ((DP.cantidad * DP.precio_unitario) * (DP.descuento / 100) ), 2) AS importe'),
                'DP.recibo',
                DB::raw("COALESCE(TC1.descripcion , '') AS desc_Tipo_Pago_1"),
                DB::raw("COALESCE(TC2.descripcion, '') AS desc_Tipo_Pago_2"),
                'CS.nombre'
            )

            ->leftJoin('productos AS PS', 'DP.articulo', '=', 'PS.numero')
            ->leftJoin('alumnos AS A', 'DP.alumno', '=', 'A.numero')
            ->leftJoin('cobranza_diaria AS CD', 'DP.recibo', '=', 'CD.recibo')
            ->leftJoin('cajeros AS CS', 'CD.cajero', '=', 'CS.numero')
            ->leftJoin('documentos_cobranza AS DC', 'DP.alumno', '=', 'DC.alumno')
            ->leftJoin(DB::raw('tipo_cobro AS TC1'), 'TC1.numero', '=', 'CD.tipo_pago_1')
            ->leftJoin(DB::raw('tipo_cobro AS TC2'), 'TC2.numero', '=', 'CD.tipo_pago_2')
            ->where('importe_cobro', '>', 0)
            ->where('PS.descripcion', '!=', null);

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

    public function getEstadodeCuenta(Request $request)
    {

        $tomaFecha = $request->input('tomafecha');
        $fecha_cobro_ini = $request->input('fecha_cobro_ini');
        $fecha_cobro_fin = $request->input('fecha_cobro_fin');
        $alumno_ini = $request->input('alumno_ini');
        $alumno_fin = $request->input('alumno_fin');

        $query = DB::table('cobranza_diaria AS CD')
        ->select(
            'A.numero as id_al',
            'A.nombre as nom_al',
            'A.fecha_nac as fecha_nac_al',
            'A.fecha_inscripcion as fecha_ins_al',
            DB::raw('COALESCE(H.horario,"") as horario_nom'),
            'DP.articulo as articulo',
            DB::raw('COALESCE(P.descripcion,"") as descripcion'),
            'DP.documento as numero_doc',
            'DP.fecha as fecha',
            'DP.precio_unitario as importe',
            'DP.recibo as recibo'
        )
        ->leftJoin('detalle_pedido AS DP','CD.recibo','=','DP.recibo')
        ->leftJoin('alumnos AS A','CD.alumno','=','A.numero')
        ->leftJoin('horarios AS H','A.horario_1','=','H.numero')
        ->leftJoin('productos AS P','P.numero','=','DP.articulo');
        
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

        $query->orderBy('id_al', 'ASC');
        $respuesta = $query->get();


        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Estado de cuenta');
        data_set($response, 'data', $respuesta);
        return response()->json($response, $response['status_code']);
    }

    public function getConsultasInscripcion()
    {
        $alumnos = DB::table('alumnos')->where('estatus', '=', 'activo')->orderBy('numero', 'ASC')->get()->toArray();
        $det_ped = DB::table('detalle_pedido')->get()->toArray();
        $productos = DB::table('productos')->get()->toArray();
        $horarios = DB::table('horarios')->get()->toArray();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Cobranza Alumnos');
        data_set($response, 'data_alumnos', $alumnos);
        data_set($response, 'data_detalle', $det_ped);
        data_set($response, 'data_productos', $productos);
        data_set($response, 'data_horarios', $horarios);
        return response()->json($response, $response['status_code']);
    }

    public function getConsultasInscripcionMes(Request $request){
        $fechaActual = new \DateTime();

        $primerDiaMes = clone $fechaActual;

        // $primerDiaMes->modify('-1 month');
        $primerDiaMes->modify('first day of this month');

        $ultimoDiaMes = clone $fechaActual;
        $ultimoDiaMes->modify('last day of this month');

        $fechaPrimerDia = $primerDiaMes->format('Y/m/d'); // Año/Mes/01
        $fechaUltimoDia = $ultimoDiaMes->format('Y/m/d'); // Año/Mes/Último día

        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $alumnos_data = DB::table('alumnos')->where('estatus', '=', 'activo')->orderBy('numero', 'ASC')->get()->toArray();
        $det_ped = DB::table('detalle_pedido')->whereBetween('fecha', [$fechaPrimerDia, $fechaUltimoDia])->get()->toArray();
        $productos = DB::table('productos')->get()->toArray();
        $horarios = DB::table('horarios')->get()->toArray();
       
        $total_inscripcion = 0;
        $alumnos = 0;
        $si_inscrito = false;

        $detalleEncontrado1 = [];
        $productoEncontrado2 = [];
        $alumnoEncontrado3 = [];
        foreach ($alumnos_data as $alumno) {
            $det_inscripcion = 0;
            $si_suma = false;
            $fecha_inscripcion = "";
            $detalleEncontrado = array_filter($det_ped, function($detalle) use ($alumno, $fechaPrimerDia, $fechaUltimoDia) {
                return $detalle->alumno === $alumno->numero &&
                       $detalle->fecha >= $fechaPrimerDia &&
                       $detalle->fecha <= $fechaUltimoDia;
            });
            // dd($detalleEncontrado);
            // array_push($detalleEncontrado1,$detalleEncontrado);
            if($detalleEncontrado){
                foreach ($detalleEncontrado as $detalle) {
                    $productoEncontrado = array_filter($productos, function($producto) use ($detalle) {
                        return $producto->ref === 'INS' &&
                         $producto->numero === $detalle->articulo;
                    });
                    // dd($productoEncontrado);
                    // array_push($productoEncontrado2,$productoEncontrado);
                    if($productoEncontrado){
                        $si_inscrito = true;
                        $si_suma = true;
                        $det_inscripcion += $detalle->precio_unitario * $detalle->cantidad;
                        $total_inscripcion += $detalle->precio_unitario * $detalle->cantidad;
                    }else{

                    }
                    $fecha_inscripcion = $detalle->fecha;
                }
                if ($si_suma) {
                    $alumnos += 1;
                }
                $det_inscripcion = 0;
            }
        }
        $resultados[] = [
            'alumnos' => $alumnos,
            'importe' => $total_inscripcion,
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $resultados);
        // data_set($response, 'detalleEncontrado', $detalleEncontrado1);
        // data_set($response, 'productoEncontrado', $productoEncontrado2);
        data_set($response, 'count_alumnos', $alumnos);
        data_set($response, 'data_alumnos', $alumnos_data);
        data_set($response, 'data_detalle', $det_ped);
        data_set($response, 'data_productos', $productos);
        data_set($response, 'data_horarios', $horarios);

        return response()->json($response, $response['status_code']);
    }

    public function getRelaciondeFacturas(Request $request)
    {
        $tomaFecha = $request->input('tomaFecha');
        $tomaCanceladas = $request->input('tomaCanceladas');
        $fecha_cobro_ini = $request->input('fecha_cobro_ini');
        $fecha_cobro_fin = $request->input('fecha_cobro_fin');
        $factura_ini = $request->input('factura_ini');
        $factura_fin = $request->input('factura_fin');

        $query = DB::table('detalle_pedido as DP')
            ->select('DP.numero_factura', 'DP.alumno', 'DP.recibo', 'DP.fecha', 'Al.razon_social', 'DP.iva', 'DP.descuento', 'DP.cantidad', 'DP.precio_unitario')
            ->leftJoin('alumnos as Al', 'Al.numero', '=', 'DP.alumno');

        if ($tomaCanceladas === true) {

            if ($tomaFecha === true) {
                $query->whereBetween('DP.fecha', [$fecha_cobro_ini, $fecha_cobro_fin]);
            }

            $query->where('DP.numero_factura', '=', 0);
        } else {
            if ($tomaFecha === true) {
                $query->whereBetween('DP.fecha', [$fecha_cobro_ini, $fecha_cobro_fin])
                    ->where('DP.numero_factura', '>', 0);
            }

            if ($factura_ini > 0 || $factura_fin > 0) {
                if ($factura_fin == 0) {
                    $query->where('DP.numero_factura', '=', $factura_ini)
                        ->where('DP.numero_factura', '>', 0);
                } else {
                    $query->whereBetween('DP.numero_factura', [$factura_ini, $factura_fin])
                        ->where('DP.numero_factura', '>', 0);
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
    public function getCredencial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required',
        ], [
            'numero.required' => 'El campo "Alumno" es obligatorio',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        try {
            $Horario_1 = "";
            $Horario_2 = "";
            $Horario_3 = "";
            $Horario_4 = "";
            $Cancha_1 = 0;
            $Cancha_2 = 0;
            $Cancha_3 = 0;
            $Cancha_4 = 0;
            $url_foto = "";
            $alumno = Alumno::where('numero', $request->numero)
                ->where('baja', "<>", "*")
                ->first();
            if ($alumno !== null) {
                //Horario1
                if ($alumno->horario_1 > 0) {
                    $horarios = Horario::where('numero', $alumno->horario_1)
                        ->where('baja', "<>", "*")
                        ->first();
                    if ($horarios !== null) {
                        $Horario_1 = $horarios->dia . " " . $horarios->horario . " (" . $horarios->cancha . ")";
                        $Cancha_1 = $horarios->cancha;
                    } else {
                        $Horario_1 = "";
                        $Cancha_1 = 0;
                    }
                }
                //Horario2
                if ($alumno->horario_2 > 0) {
                    $horarios = Horario::where('numero', $alumno->horario_2)
                        ->where('baja', "<>", "*")
                        ->first();
                    if ($horarios !== null) {
                        $Horario_2 = $horarios->dia . " " . $horarios->horario . " (" . $horarios->cancha . ")";
                        $Cancha_2 = $horarios->cancha;
                    } else {
                        $Horario_2 = "";
                        $Cancha_2 = 0;
                    }
                }
                //Horario3
                if ($alumno->horario_3 > 0) {
                    $horarios = Horario::where('numero', $alumno->horario_3)
                        ->where('baja', "<>", "*")
                        ->first();
                    if ($horarios !== null) {
                        $Horario_3 = $horarios->dia . " " . $horarios->horario . " (" . $horarios->cancha . ")";
                        $Cancha_3 = $horarios->cancha;
                    } else {
                        $Horario_3 = "";
                        $Cancha_3 = 0;
                    }
                }
                //Horario4
                if ($alumno->horario_4 > 0) {
                    $horarios = Horario::where('numero', $alumno->horario_4)
                        ->where('baja', "<>", "*")
                        ->first();
                    if ($horarios !== null) {
                        $Horario_4 = $horarios->dia . " " . $horarios->horario . " (" . $horarios->cancha . ")";
                        $Cancha_4 = $horarios->cancha;
                    } else {
                        $Horario_4 = "";
                        $Cancha_4 = 0;
                    }
                }
                $resultados = [
                    'alumno' => $alumno,
                    'horario_1' => $Horario_1,
                    'horario_2' => $Horario_2,
                    'horario_3' => $Horario_3,
                    'horario_4' => $Horario_4,
                    'cancha_1' => $Cancha_1,
                    'cancha_2' => $Cancha_2,
                    'cancha_3' => $Cancha_3,
                    'cancha_4' => $Cancha_4,
                ];
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria | Credencial del Alumno');
                data_set($response, 'data', $resultados);
                return response()->json($response, $response['status_code']);
            } else {
                $response = ObjectResponse::CatchResponse("No se encuentra el alumno.");
                return response()->json($response, 404);
            }
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
}
