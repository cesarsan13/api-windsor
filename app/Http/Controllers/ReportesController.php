<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Horario;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Query\Builder;


class ReportesController extends Controller
{
 public function getAlumnosPorClaseSemanal(Request $request){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $horario= Horario::where('numero','=',$request->horario)->get();
            $idHorario =$request->horario;
            $alumnosHorario1 = Alumno::where('baja','<>','*')
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
            ->orderBy($request->orden, 'ASC' )->get(['id','a_nombre','fecha_nac']);
            $idHorario2=2;
            $alumnosHorario2 = Alumno::where('baja','<>','*')
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
            ->orderBy($request->orden, 'ASC' )->get(['id','a_nombre','fecha_nac']);
            $rep_dos_sel =ObjectResponse::Rep_Dos_Sel(100);
            $rep_dos_sel=ObjectResponse::PrepHorario($alumnosHorario1,$rep_dos_sel,1);
            $rep_dos_sel=ObjectResponse::PrepHorario($alumnosHorario2,$rep_dos_sel,2);
            $reporte=[
                "horario"=>$horario,
                "data"=>$rep_dos_sel,
            ];
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro');
            data_set($response,'data',$reporte);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
}