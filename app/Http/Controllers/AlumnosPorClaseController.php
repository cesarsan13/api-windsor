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

    protected $rules = [
        'numero_1' => 'nullable|max:110', 
        'nombre_1'  => 'nullable|string|max:50', 
        'año_nac_1' => 'nullable|string|max:15', 
        'mes_nac_1' => 'nullable|string|max:15', 
        'telefono_1' => 'nullable|string|max:15', 
        'numero_2' => 'nullable|max:110', 
        'nombre_2' => 'nullable|string|max:50',
        'año_nac_2' => 'nullable|string|max:15', 
        'mes_nac_2' => 'nullable|string|max:15', 
        'telefono_2' => 'nullable|string|max:15',
        'baja' => 'nullable|string|max:1',
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
        
        return $AlumnosPC ;
    }



    public Function UpdateRepDosSel($idHorario1RDS, $idHorario2RDS, $ordenRDS){

        $delete = DB::table('rep_dos_sels')->delete();

        $ids = range(1, 32);

        for ($i = 1; $i <= 32; $i++) {
            $values[] = ['numero' => $i];
        }

        $Insert = DB::table('rep_dos_sels')->insert($values);

        //Para guardar el horario 1
        $num1 = 0;
        $inserthorario1 = $this->getListaHorariosAPC($idHorario1RDS, $ordenRDS);
        $datosParaInsertar1 = $inserthorario1->toArray();

        foreach($datosParaInsertar1 as $item){
            $updateData1 = [
                'numero_1' => $item->id, 
                'nombre_1' => $item->nombre,
                'año_nac_1' => $item->fecha_nac,
                'mes_nac_1' => $item->fecha_nac,
                'telefono_1' => $item->telefono_1,
            ]; 
            $num1++;
            $updateh1 = DB::table('rep_dos_sels')->where('numero', $num1)->update($updateData1);
        }

        //Para guardar el horario 2
        $num2 = 0;
        $inserthorario2 = $this->getListaHorariosAPC($idHorario2RDS, $ordenRDS);
        $datosParaInsertar2 = $inserthorario2->toArray();
        foreach($datosParaInsertar2 as $item){
            $updateData2 = [
                'numero_2' => $item->id, 
                'nombre_2' => $item->nombre,
                'año_nac_2' => $item->fecha_nac,
                'mes_nac_2' => $item->fecha_nac,
                'telefono_2' => $item->telefono_1,
            ]; 
            $num2++;
            $updateh2 = DB::table('rep_dos_sels')->where('numero', $num2)->update($updateData2);
        }

        //Se trae los datos para la impresion
        $response  = ObjectResponse::DefaultResponse();
        $RepDosSelGet = DB::table('rep_dos_sels')->where('baja', '<>', '*')->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data"=> [$RepDosSelGet]
            
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $RepDosSelGet);
        return response()->json($response, $response['status_code']);

    }


}
