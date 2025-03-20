<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Clases;
use App\Models\Calificaciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConcentradoCalificacionesController extends Controller
{
    public function getMateriasPorGrupo($idHorario){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $resultados = Clases::leftJoin('asignaturas as M', 'clases.materia', '=', 'M.numero')
                ->select('M.numero', 'M.descripcion', 'M.area')
                ->where('clases.baja', ' ')
                ->where('M.baja', '!=', '*')
                ->where('grupo', $idHorario)
                ->orderBy('M.area')
                ->orderBy('M.orden')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    function getInfoActividadesXGrupo($idHorario, $idBimestre){
        $response = ObjectResponse::DefaultResponse();
        try{
            $resultados = DB::table('calificaciones as C')
            ->select('C.alumno as numero', 'C.bimestre', 'C.materia', 'C.actividad', 'C.unidad', 'C.calificacion', 'M.area')
            ->leftJoin('horarios as H', 'H.horario', '=', 'C.grupo')
            ->leftJoin('alumnos as A', 'A.numero', '=', 'C.alumno')
            ->leftJoin('asignaturas as M', 'M.numero', '=', 'C.materia')
            ->where('H.numero','=', $idHorario)
            ->where('C.bimestre', '=', $idBimestre)
            ->get();

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    
    function getActividadesReg(){
        $response = ObjectResponse::DefaultResponse();
        try{
            $resultados = DB::table('actividades')
            ->select('materia', 'secuencia' ,'EB1', 'EB2', 'EB3', 'EB4', 'EB5')
            ->where('baja', '!=', '*')
            ->orderBy('materia', 'ASC')
            ->orderBy('secuencia', 'ASC')
            ->get();

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }


    function getMateriasReg($idHorario){
        $response = ObjectResponse::DefaultResponse();
        try{
            $resultados = DB::table('asignaturas as M')
            ->select('M.numero', 'M.descripcion', 'M.evaluaciones', 'M.actividad', 'M.area')
            ->leftJoin('clases as C', 'C.materia', '=', 'M.numero')
            ->where('M.baja', '<>', '*')
            ->where('C.baja', '<>', '*')
            ->where('C.grupo', '=', $idHorario)
            ->get();

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria aaa');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    function getAlumno($idHorario){
        $response = ObjectResponse::DefaultResponse();
        try{
            $resultados = DB::table('alumnos')
            ->select('numero', 'nombre')
            ->where('grupo','=', $idHorario)
            ->where('baja', '!=', '*')
            ->orderBy('nombre')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    function getActividadesXHorarioXAlumnoXMateriaXBimestre($idHorario, $idAlumno, $idMateria, $idBimestre){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $resultados = DB::table('calificaciones as C')
            ->select('C.alumno', 'C.bimestre', 'C.materia', 'C.actividad', 'C.unidad', 'C.calificacion')
            ->leftJoin('horarios as H', 'H.horario', '=', 'C.grupo')
            ->where('H.numero','=', $idHorario)
            ->where('C.bimestre', '=', $idBimestre)
            ->where('C.alumno', '=', $idAlumno)
            ->where('C.materia', '=', $idMateria)
            ->orderBy('C.materia')
            ->ordeRby('C.actividad')
            ->orderBy('C.unidad')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    
    function getActividadesPorMateria($idMateria){
        $response = ObjectResponse::DefaultResponse();

        try{
            $resultados = DB::table('actividades')
            ->select('materia', 'secuencia', 'descripcion', 'EB1', 'EB2', 'EB3', 'EB4', 'EB5')
            ->where('materia', '=', $idMateria)
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
}

