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
}
