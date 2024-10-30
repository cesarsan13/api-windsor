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
        $resultados = Clases::leftJoin('materias', 'clases.materia', '=', 'materias.numero')
            ->select('materias.numero', 'materias.descripcion', 'materias.area', 'materias.caso_evaluar', 'materias.area')
            ->where('clases.baja', ' ')
            ->where('materias.baja', '')
            ->where('grupo', $idHorario)
            ->orderBy('materias.area')
            ->orderBy('materias.orden')->get();
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
         ->get();
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
            ->select('C.alumno', 'A.nombre', 'C.bimestre', 'C.materia', 'C.actividad', 'C.unidad', 'C.calificacion', 'M.area')
            ->leftJoin('horarios as H', 'H.horario', '=', 'C.grupo')
            ->leftJoin('alumnos as A', 'A.numero', '=', 'C.alumno')
            ->leftJoin('materias as M', 'M.numero', '=', 'C.materia')
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
    

}
