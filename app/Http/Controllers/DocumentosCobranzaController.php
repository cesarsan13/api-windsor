<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentosCobranzaController extends Controller
{
    public function imprimir($fecha, $grupo)
    {
        $documentosgrupos = DB::table('documentos_cobranza')
            ->where('fecha', '<=', $fecha)
            ->where('importe_pago', '=', 0)
            ->where('descuento', '<', 100);

        if ($grupo === 1) {
            $documentosgrupos->orderBy('grupo')->orderBy('orden')->orderBy('alumno')->orderBy('producto')->orderBy('fecha');
        } else {
            $documentosgrupos->orderBy('alumno')->orderBy('producto')->orderBy('fecha');
        }

        $result = $documentosgrupos->get();

        // $docuemntosAlumnos=DB::table('Documentos_Cobranza')
        // ->select('Alumno', DB::raw('COUNT(*) as Incide'))
        // ->where('Fecha', '<=', $fechaInicial)
        // ->where('Importe_Pago', 0)
        // ->whereRaw('(Importe - (Importe * descuento/100)) > 1')
        // ->where('Alumno', $alumno)
        // ->groupBy('Alumno')
        // ->get();
    }
}
