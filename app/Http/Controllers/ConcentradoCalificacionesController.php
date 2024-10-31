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
                ->select('materias.numero', 'materias.descripcion', 'materias.area')
                ->where('clases.baja', ' ')
                ->where('materias.baja', ' ')
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
            ->select('C.alumno as numero', 'A.nombre', 'C.bimestre', 'C.materia', 'C.actividad', 'C.unidad', 'C.calificacion', 'M.area')
            ->leftJoin('horarios as H', 'H.horario', '=', 'C.grupo')
            ->leftJoin('alumnos as A', 'A.numero', '=', 'C.alumno')
            ->leftJoin('materias as M', 'M.numero', '=', 'C.materia')
            ->where('H.numero','=', $idHorario)
            ->where('C.bimestre', '=', $idBimestre)
            //->orderBy('A.nombre', 'ASC')
            ->get();

            //Agrupar los resultados por alumno
           // $alumnosagrupados = $resultados->groupBy('numero')->map(function( $actividades, $numero){
                //dd($numero,  $nombre);
                //$nombre = $actividades->first()->nombre;
                //$bimestre = $actividades->first()->bimestre;
                //return[
                //    'numero'=>$numero,
                //    'nombre'=> $nombre,
                //    'bimestre'=>$bimestre,
                //    'actividades' => $actividades->map(
                //            function($actividad){
                //                return [
                //                    'materia' => $actividad->materia,
                //                    'actividad' => $actividad->actividad,
                //                    'unidad' => $actividad->unidad,
                //                    'calificacion' => $actividad->calificacion,
                //                    'area' => $actividad->area,
                //                    //'bimestre' => $actividad->bimestre
                //                ];
                //            })
                //        ]; 
            //})->values();

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

    function getMateriasReg(){
        $response = ObjectResponse::DefaultResponse();
        try{
            $resultados = DB::table('materias')
            ->select('numero', 'descripcion', 'evaluaciones', 'actividad')
            ->where('baja', '!=', '*')
            ->get();

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'data', $resultados);
            data_set($response, 'message', 'peticion satisfactoria');
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
}

