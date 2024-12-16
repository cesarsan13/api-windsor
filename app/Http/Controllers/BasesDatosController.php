<?php

namespace App\Http\Controllers;

use App\Models\BasesDatos;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BasesDatosController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
    ];
    protected $rules= [
        'id'=>'required|integer',
        'nombre'=>'required|string',
        'host'=>'required|string',
        'port'=>'required|integer',
        'username'=>'required|string',
        'password'=>'required|string',
        'clave_propietario'=>'required|string',
        'proyecto'=>'required|string',
    ];
    public function index() {
        $response = ObjectResponse::CorrectResponse();
        try {
            $baseDatos = DB::table('bases_datos')->select('*')->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de base datos');
            data_set($response, 'data', $baseDatos);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function save(Request $request){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        $basesDatos = new BasesDatos();
        $basesDatos->id = $request->id;
        $basesDatos->nombre = $request->nombre;
        $basesDatos->host=$request->host;
        $basesDatos->port=$request->port;
        $basesDatos->username=$request->username;
        $basesDatos->password=$request->password;
        $basesDatos->clave_propietario=$request->clave_propietario;
        $basesDatos->proyecto=$request->proyecto;
        $basesDatos->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'base datos registrado.');
        data_set($response, 'alert_icon', 'success');
        data_set($response, 'data', $basesDatos);
        return response()->json($response, $response['status_code']);
    }
    public function update(Request $request) {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        $baseDato= BasesDatos::find($request->id);
        if(!$baseDato){
            $response = ObjectResponse::CatchResponse('base datos no encontrado');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }        
        $baseDato->nombre = $request->nombre;
        $baseDato->host=$request->host;
        $baseDato->port=$request->port;
        $baseDato->username=$request->username;
        $baseDato->password=$request->password;
        $baseDato->clave_propietario=$request->clave_propietario;
        $baseDato->proyecto=$request->proyecto;
        $baseDato->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'base datos actualizado.');
        data_set($response, 'data', $baseDato);
        data_set($response, 'alert_icon', 'success');
        return response()->json($response, $response['status_code']);
    }
}
