<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cobranza_Diaria;

class DocumentosCobranzaController extends Controller
{
    public function imprimir($fecha, $grupo = 0)
    {
        $response = ObjectResponse::DefaultResponse();
        $documentosgrupos = DB::table('documentos_cobranza')
            ->join('productos', 'documentos_cobranza.producto', '=', 'productos.id')
            ->where('fecha', '<=', $fecha)
            ->where('importe_pago', '=', 0)
            ->where('descuento', '<', 100)
            ->select('documentos_cobranza.*', 'productos.descripcion');

        if ($grupo === 1) {
            $documentosgrupos->orderBy('grupo')->orderBy('orden')->orderBy('alumno')->orderBy('producto')->orderBy('fecha');
        } else {
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
            ->select('id', 'nombre', 'telefono_1', 'estatus')->get();
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
            ->select('alumnos.horario_1', 'alumnos.nombre', 'alumnos.baja', 'alumnos.id', 'horarios.horario')
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
    
}
