<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;


class AlumnosPorClaseController extends Controller
{
    protected $messages=[
        'required' => 'El campo :attribute es obligatorio.',
    ];
    
    public function getHorariosAPC(){
        $response  = ObjectResponse::DefaultResponse();
        $horario = DB::table('horarios')->select('numero', 'horario')->where("baja",'<>','*')-> get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$horario]
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $horario);
        return response()->json($response, $response['status_code']);
    }

    public function getListaHorariosAPC($idHorario, $orden){
        $response  = ObjectResponse::DefaultResponse();
        $AlumnosPC = DB::table ('alumnos')
        ->select('nombre','id','fecha_nac','telefono_1')
        ->where("baja",'<>','*')
        ->where(function ($query) use ($idHorario){
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
        ->orderBy($orden, 'ASC' )->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$AlumnosPC]
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $AlumnosPC);
        return response()->json($response, $response['status_code']);
    }

}
