<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cobranza_Diaria;
use Illuminate\Support\Facades\Log;

class DocumentosCobranzaController extends Controller
{
    public function imprimir($fecha, $grupo = 0)
    {
        $response = ObjectResponse::DefaultResponse();
        $formatFecha = str_replace("-","/",$fecha);        
        $documentosgrupos = DB::table('documentos_cobranza')
            ->join('productos', 'documentos_cobranza.producto', '=', 'productos.numero')
            ->where('fecha', '<=', $formatFecha)
            ->where('importe_pago', '=', 0)
            ->where('descuento', '<', 100)
            ->select('documentos_cobranza.*', 'productos.descripcion');

        if ((int) $grupo === 1) {
            Log::info("por grupo");
            $documentosgrupos->orderBy('grupo')->orderBy('orden')->orderBy('alumno')->orderBy('producto')->orderBy('fecha');
        } else {
            Log::info("por numero");
            $documentosgrupos->orderBy('alumno')->orderBy('producto')->orderBy('fecha');
        }

        $documentos = $documentosgrupos->get();
        $documentosAlumnosIncides = DB::table('documentos_cobranza')
            ->select('Alumno', DB::raw('COUNT(*) as Incide'))
            ->where('Fecha', '<=', $fecha)
            ->where('Importe_Pago', 0)
            ->whereRaw('(Importe - (Importe * Descuento/100)) > 1')
            ->groupBy('Alumno')
            ->get();
        $alumnos = DB::table('alumnos')
            ->select('numero', 'nombre', 'telefono1', 'estatus')->get();
        $data = [
            "documentos" => $documentos,
            "indeces" => $documentosAlumnosIncides,
            "alumnos" => $alumnos,
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $data);
        return response()->json($response, $response['status_code']);
    }
    public function get_Grupo_Cobranza()
    {
        $alumnos = DB::table('alumnos')
            ->join('horarios', 'alumnos.horario_1', '=', 'horarios.numero')
            ->join('documentos_cobranza', 'alumnos.numero', '=', 'documentos_cobranza.alumno')
            ->select('alumnos.horario_1', 'alumnos.nombre', 'alumnos.baja', 'alumnos.numero', 'horarios.horario')
            ->distinct()
            ->orderBy('horario_1')
            ->orderBy('nombre')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $alumnos);
        return response()->json($response, $response['status_code']);
    }
    public function poner_Grupo_Cobranza(Request $request)
    {
        $documento = DB::table('documentos_cobranza')
            ->where('alumno', $request->alumno)
            ->first();

        if ($documento) {
            DB::table('documentos_cobranza')
                ->where('alumno', $request->alumno)
                ->update([
                    'grupo' => $request->nomGrupo,
                    'orden' => $request->numOrd,
                    'baja' => $request->baja,
                ]);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        } else {
            $response = ObjectResponse::BadResponse("error");
            data_set($response, 'message', 'No se encontró ningún documento para actualizar');
        }
        return response()->json($response, $response['status_code']);
    }
    public function getCobranzaFiltrada(Request $request)
    {
        $fechaInicial = $request->input('fecha_inicial');
        $alumnoIni = $request->input('alumno_ini');
        $alumnoFin = $request->input('alumno_fin');
        $sinDeudores = $request->input('sin_deudores', 0);
        $grupoAlumno = $request->input('grupo_alumno', 0);
        $docs = DB::table('documentos_cobranza')
            ->leftJoin('productos', 'documentos_cobranza.producto', '=', 'productos.numero')
            ->leftJoin('alumnos', 'documentos_cobranza.alumno', '=', 'alumnos.numero')
            ->select(
                'documentos_cobranza.alumno',
                'alumnos.nombre as alumno_nombre',
                'documentos_cobranza.producto',
                'productos.descripcion AS producto_descripcion',
                'documentos_cobranza.numero_doc',
                'documentos_cobranza.fecha',
                'documentos_cobranza.importe',
                'documentos_cobranza.importe_pago',
                'documentos_cobranza.descuento',
                DB::raw('((documentos_cobranza.importe - documentos_cobranza.importe_pago) - (documentos_cobranza.importe * documentos_cobranza.descuento / 100)) AS tw_saldo')
            )
            ->where('documentos_cobranza.fecha', '<=', $fechaInicial)
            ->whereRaw('((documentos_cobranza.importe - documentos_cobranza.importe_pago) - (documentos_cobranza.importe * documentos_cobranza.descuento / 100)) > 1')
            ->where(function ($query) use ($alumnoIni, $alumnoFin) {
                $query->whereBetween('documentos_cobranza.alumno', [$alumnoIni, $alumnoFin])
                    ->orWhere('documentos_cobranza.alumno', '=', $alumnoIni);
            })
            ->when($sinDeudores, function ($query) {
                $query->where('documentos_cobranza.grupo', '<>', 'DEUDOR');
            })
            ->when($grupoAlumno, function ($query) {
                $query->orderBy('documentos_cobranza.grupo')
                    ->orderBy('documentos_cobranza.orden');
            })
            ->orderBy('documentos_cobranza.alumno')
            ->orderBy('documentos_cobranza.producto')
            ->orderBy('documentos_cobranza.fecha')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Lista de Docs. Cobranza');
        data_set($response, 'data', $docs);
        return response()->json($response, $response['status_code']);
    }
}
