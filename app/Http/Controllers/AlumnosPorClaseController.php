<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function getListaHorariosAPC(){
        $response  = ObjectResponse::DefaultResponse();

    }

}
