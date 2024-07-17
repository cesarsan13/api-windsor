<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepDosSel;

class RepDosSelController extends Controller
{
    public function siguiente(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = RepDosSel::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Siguiente Report');
            data_set($response,'alert_text','Siguiente Report');
            data_set($response,'data',$siguiente);

        } catch (\Excepction $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
    public function UpdateRepDosSel(Request $request, RepDosSel $RepDosSel) {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response,$response['status_code']);
        }
        try {
            $repDosSel = RepDosSel::where('numero',$request->numero)
                ->update(["numero_1"=>$request->numero_1,
                        "nombre_1"=>$request->nombre_1,
                        "año_nac_1"=>$request->año_nac_1,
                        "mes_nac_1"=>$request->mes_nac_1,
                        "telefono_1"=>$request->telefono_1,
                        "nombre_2"=>$request->nombre_2,
                        "año_nac_2"=>$request->año_nac_2,
                        "mes_nac_2"=>$request->mes_nac_2,
                        "telefono_2"=>$request->telefono_2,
                        ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Report actualizado');
            data_set($response,'alert_text','Repor actualizado');
        } catch (\Exception $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
                data_set($response, 'message', 'Peticion fallida | Actualizacion de Report');
                data_set($response, 'data', $ex);
        }
        return response()->json($response,$response['status_code']);
    }
}
