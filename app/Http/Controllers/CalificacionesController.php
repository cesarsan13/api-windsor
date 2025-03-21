<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Alumno;
use App\Models\Calificaciones;
use App\Models\Clases;
use App\Models\Materias;
use App\Models\Asignaturas;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CalificacionesController extends Controller
{
    public function getMaterias(Request $request)
    {
        $rules = ['grupo' => 'required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $resultados = Clases::leftJoin('asignaturas as M', 'clases.materia', '=', 'M.numero')
            ->select('M.numero', 'M.descripcion', 'M.area', 'M.caso_evaluar')
            ->where('clases.baja','<>', '*' )
            ->where('M.baja','<>','*')
            ->where('grupo', $request->grupo)
            ->orderBy('M.area')
            ->orderBy('M.orden')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $resultados);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function getNewCalificacionesMateria(Request $request)
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
        $materias = Asignaturas::select('numero', 'actividad', 'evaluaciones')->get();
        $calificaciones = Calificaciones::select('alumno', 'bimestre', 'materia', 'actividad', 'unidad', 'calificacion')
            ->where('grupo', $request->grupo)
            ->get();
        $actividades = Actividad::select('materia', 'secuencia', 'EB1', 'EB2', 'EB3', 'EB4', 'EB5')->get();
        $data = ["materias" => $materias, "calificaciones" => $calificaciones, "actividades" => $actividades];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $data);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }

    public function getCalificacionesAlumnosArea1(Request $request)
    {
        $rules = [
            'grupo' => 'required',
            'grupo_nombre' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        $alumno = Alumno::select('numero','nombre')->where('grupo', '=', $request->grupo)->get();

        $materiasArea1 = Clases::select('clases.materia', 'M.actividad', 'M.descripcion', 'M.area', 'M.evaluaciones')
            ->leftJoin('asignaturas as M', 'clases.materia', '=', 'M.numero')
            ->where('clases.grupo', '=', $request->grupo)
            ->where('M.area', '=', '1')
            ->where('M.baja', '<>', '*')
            ->where('clases.baja', '<>', '*')
            ->get();
        $materiasArea4 = Clases::select('clases.materia', 'M.actividad', 'M.descripcion', 'M.area', 'M.evaluaciones')
            ->leftJoin('asignaturas as M', 'clases.materia', '=', 'M.numero')
            ->where('clases.grupo', '=', $request->grupo)
            ->where('M.area', '=', '4')
            ->where('M.baja', '<>', '*')
            ->where('clases.baja', '<>', '*')
            ->get();

        $calificaciones = Calificaciones::select('alumno', 'bimestre', 'materia', 'actividad', 'unidad', 'calificacion')
            ->where('grupo', $request->grupo_nombre)
            ->get();

        $actividades = Actividad::select('materia', 'secuencia', 'EB1', 'EB2', 'EB3', 'EB4', 'EB5')->get();

        $data = [
            "materiasArea1" => $materiasArea1,
            "materiasArea4" => $materiasArea4,
            "calificaciones" => $calificaciones,
            "actividades" => $actividades,
            "alumnos" => $alumno
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $data);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }
    public function getCalificacionesMateria(Request $request)
    {
        $rules = [
            'grupo' => 'required',
            'materia' => 'required',
            'area' => 'required',
            'bimestre' => 'required',
            'alumno' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validación');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $materia = Asignaturas::select('actividad', 'evaluaciones')->where('numero', $request->materia)->first();
        $calificaciones = Calificaciones::select('alumno', 'bimestre', 'materia', 'actividad', 'unidad', 'calificacion')
            ->where('grupo', $request->grupo)
            ->get();
        $data = [];
        if (!$materia) {
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $data);
            data_set($response, 'message', 'peticion satisfactoria');
            return response()->json($response, $response['status_code']);
        }
        if ($request->area === 1 || $request->area === 4) {
            if ($materia->actividad === "no") {
                $cali = Calificaciones::where('grupo', $request->grupo)
                    ->where('alumno', $request->alumno)
                    ->where('actividad', '0')
                    ->where('materia', $request->materia)
                    ->where('unidad', '<=', $materia->evaluaciones)
                    ->sum('calificacion')->get();
                $suma = 0;
                if (!$cali->calificacion) {
                    $suma = 0.0;
                } else {
                    $suma = $cali->calificacion;
                    $evaluaciones = $materia->evaluaciones;
                }
                $data = [
                    "calificacion" => $suma
                ];
            } else {
                // Si hay actividades, obtener las actividades correspondientes
                $cali = Actividad::select('secuencia', 'EB1', 'EB2', 'EB3', 'EB4', 'EB5')
                    ->where('materia', $request->materia)
                    ->orderBy('secuencia')
                    ->get();
                $suma = 0;
                $evaluaciones = 0;
                foreach ($cali as $cal) {
                    // Aplicar el filtro sobre las calificaciones
                    $filteredCalificaciones = $calificaciones->filter(function ($calificacion) use ($cal, $request) {
                        // Filtrar por alumno, materia, bimestre, actividad y unidad
                        return $calificacion->alumno == $request->alumno &&
                            $calificacion->materia == $request->materia &&
                            $calificacion->bimestre == $request->bimestre  &&
                            $calificacion->actividad == $cal->secuencia &&
                            $calificacion->unidad <= $cal->{'EB' . ($request->bimestre)};
                    });                   
                    $cpa = $filteredCalificaciones->sum('calificacion');
                    if ($cpa > 0) {
                        $nose = $cal->{'EB' . ($request->bimestre)};
                        $suma += ($cpa / $nose);
                        $evaluaciones += 1;
                    }
                }
                if ($suma === 0 || $evaluaciones === 0) {
                    $cal = 0;
                    $data = [
                        "calificacion" => $cal
                    ];
                } else {
                    $calificacion = ($suma / $evaluaciones);
                    if ($calificacion < 5.0) {
                        $calificacion = 5.0;
                        $data = [
                            "calificacion" => $calificacion
                        ];
                    } else {
                        $calificacion = ($suma / $evaluaciones);
                        $data = [
                            "calificacion" => $calificacion
                        ];
                    }
                }
            }
        } else {
            $cali = Calificaciones::where('grupo', $request->grupo)
                ->where('alumno', $request->alumno)
                ->where('materia', $request->materia)
                ->where('bimestre', '=', $request->bimestre)
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
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $data);
        data_set($response, 'message', 'peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }
}
