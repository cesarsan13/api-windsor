<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use App\Models\RepDosSel;
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

    public function getListaHorariosAPC($idHorario1, $idHorario2, $orden){
        $AlumnosPC1 = DB::table ('alumnos')
        ->select('nombre','id', DB::raw('SUBSTRING(fecha_nac, 1, 4) AS año_nac'),DB::raw('SUBSTRING(fecha_nac, 6, 2) AS mes_nac'),'telefono_1')
        ->where("baja",'<>','*')
        ->where(function ($query) use ($idHorario1){
        $query->orWhere("horario_1", "=", $idHorario1)
            ->orWhere("horario_2", "=", $idHorario1)
            ->orWhere("horario_3", "=", $idHorario1)
            ->orWhere("horario_4", "=", $idHorario1)
            ->orWhere("horario_5", "=", $idHorario1)
            ->orWhere("horario_6", "=", $idHorario1)
            ->orWhere("horario_7", "=", $idHorario1)
            ->orWhere("horario_8", "=", $idHorario1)
            ->orWhere("horario_9", "=", $idHorario1)
            ->orWhere("horario_10", "=", $idHorario1)
            ->orWhere("horario_11", "=", $idHorario1)
            ->orWhere("horario_12", "=", $idHorario1)
            ->orWhere("horario_13", "=", $idHorario1)
            ->orWhere("horario_14", "=", $idHorario1)
            ->orWhere("horario_15", "=", $idHorario1)
            ->orWhere("horario_16", "=", $idHorario1)
            ->orWhere("horario_17", "=", $idHorario1)
            ->orWhere("horario_18", "=", $idHorario1)
            ->orWhere("horario_19", "=", $idHorario1)
            ->orWhere("horario_20", "=", $idHorario1);
        })
        ->orderBy($orden, 'ASC' )->get();

        $AlumnosPC2 = DB::table ('alumnos')
        ->select('nombre','id',DB::raw('SUBSTRING(fecha_nac, 1, 4) AS año_nac'),DB::raw('SUBSTRING(fecha_nac, 6, 2) AS mes_nac'),'telefono_1')
        ->where("baja",'<>','*')
        ->where(function ($query) use ($idHorario2){
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
        ->orderBy($orden, 'ASC' )->get();

        $impHorarios = [
            'data1' => $AlumnosPC1,
            'data2' => $AlumnosPC2
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $impHorarios);

        return response()->json($response, $response['status_code']);
    }

}
