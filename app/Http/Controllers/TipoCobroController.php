<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\TipoCobro;
use Illuminate\Support\Facades\Validator;
class TipoCobroController extends Controller
{
    protected   $messages=[
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
        ];
         protected $rules = [
            'id'=> 'required|integer',
            'descripcion'=>'required|string|max:50',
            'comision'=>'nullable|float',
            'aplicacion'=>'nullable|string|max:30',
            'cue_banco'=>'nullable|string|max:34',
            'baja'=>'nullable|string|max:1',
    ];
     public function index(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $tipos_cobro = TipoCobro::where("baja",'<>','*')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro');
            data_set($response,'data',$tipos_cobro);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
     public function indexBaja(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $tipos_cobro = TipoCobro::where("baja",'=','*')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro inactivos');
            data_set($response,'data',$tipos_cobro);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
        public function siguiente(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = TipoCobro::max('id');
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Siguiente tipo de cobro');
            data_set($response,'alert_text','Siguiente tipo de cobro');
            data_set($response,'data',$siguiente);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response,$response['status_code']);
        }
        try {
                $datosFiltrados = $request->only([
                    'id',
                    'descripcion',
                    'comision',
                    'aplicacion',
                    'cue_banco',
                ]);
            $nuevoCobro = TipoCobro::create([
                "id"=>$datosFiltrados['id'],
                "descripcion"=>$datosFiltrados['descripcion'],
                        "comision"=>$datosFiltrados['comision'],
                        "aplicacion"=>$datosFiltrados['aplicacion'] ?? '',
                        "cue_banco"=>$datosFiltrados['cue_banco'] ?? '',
                        "baja"=>$datosFiltrados['baja'] ?? '',
                        ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Tipo de Cobro registrado.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response['status_code']);
    }
    public function update(Request $request, TipoCobro $tipo_cobro){
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response,$response['status_code']);
        }
        try {
            $tipo_cobro = TipoCobro::where('id',$request->id)
                ->update(["descripcion"=>$request->descripcion,
                        "comision"=>$request->comision,
                        "aplicacion"=>$request->aplicacion ?? '',
                        "cue_banco"=>$request->cue_banco ?? '',
                        "baja"=>$request->baja ?? '',
                        ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Tipo de cobro actualizado');
            data_set($response,'alert_text','Tipo de Cobro actualizado');
        } catch (\Exception $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
                data_set($response, 'message', 'Peticion fallida | Actualizacion de Tipo de Cobro');
                data_set($response, 'data', $ex);
        }
        return response()->json($response,$response['status_code']);
    }
}
