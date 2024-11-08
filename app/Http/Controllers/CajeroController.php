<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Cajeros;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

class CajeroController extends Controller
{
    public function index() {
        $response = ObjectResponse::DefaultResponse();
        try{
            $caj = Cajeros::select('*')->where('baja','')
            ->get()
            ->makeHidden(['created_at', 'updated_at']);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria. Lista de roles:');
            data_set($response, 'data', $caj);
        }catch(\Exception $ex){
            $response  = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
    public function siguiente(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Cajeros::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Siguiente Cajero');
            data_set($response,'alert_text','Siguiente Cajero');
            data_set($response,'data',$siguiente);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
    public function UpdateCajeros(Request $request, Cajeros $Cajero) {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response,$response['status_code']);
        }
        try {
            $cajero = Cajeros::where('numero',$request->numero)
                ->update(["nombre"=>$request->nombre,
                        "direccion"=>$request->direccion,
                        "colonia"=>$request->colonia,
                        "estado"=>$request->estado,
                        "telefono"=>$request->telefono,
                        "fax"=>$request->fax,
                        "mail"=>$request->mail,
                        "baja"=>$request->baja ?? '',
                        "clave_cajero"=>$request->clave_cajero,
                        ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Cajero actualizado');
            data_set($response,'alert_text','Cajero actualizado');
        } catch (\Exception $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
                data_set($response, 'message', 'Peticion fallida | Actualizacion de Cajero');
                data_set($response, 'data', $ex);
        }
        return response()->json($response,$response['status_code']);
    }

    protected $rules = [
        'numero'=> 'required|integer',
        'nombre'=>'required|string|max:50',
        'direccion'=>'required|string|max:50',
        'colonia'=>'required|string|max:50',
        'estado'=>'required|string|max:50',
        'telefono'=>'required|string|max:20',
        'fax'=>'required|string|max:50',
        'mail'=>'required|string|max:50',
        'baja'=>'nullable|string|max:1',
        'clave_cajero'=>'required|string|max:50',
                ];
    protected   $messages=[
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'unique' => 'El campo :attribute ya ha sido registrado',
                ];
    public function PostCajeros(Request $request){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response,$response['status_code']);
        }
        try {
                $datosFiltrados = $request->only([
                    'numero',
                    'nombre',
                    'direccion',
                    'colonia',
                    'estado',
                    'telefono',
                    'fax',
                    'mail',
                    'baja',
                    'clave_cajero',
            ]);
        $nuevoCajero = Cajeros::create([
                "numero"=>$datosFiltrados['numero'],
                "nombre"=>$datosFiltrados['nombre'],
                "direccion"=>$datosFiltrados['direccion'],
                "colonia"=>$datosFiltrados['colonia'],
                "estado"=>$datosFiltrados['estado'],
                "telefono"=>$datosFiltrados['telefono'],
                "fax"=>$datosFiltrados['fax'],
                "mail"=>$datosFiltrados['mail'],
                "baja"=>$datosFiltrados['baja'] ?? '',
                "clave_cajero"=>$datosFiltrados['clave_cajero'],
                ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response,'message','peticion satisfactoria | Cajero registrado.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response['status_code']);
    }
    public function indexBaja(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $cajeros = Cajeros::where("baja",'=','*')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de Cajeros inactivos');
            data_set($response,'data',$cajeros);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
}
