<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\Referencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReferenciaController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',                        
        'string' => 'El campo :attribute debe ser una cadena.',
        'integer' => 'El campo :attribute debe ser un número.',        
    ];
    protected $rules=[
        'numero'=>'required|integer',
        'referencia'=>'required|string',
        'descripcion'=>'required|string',
        'baja'=>'nullable|string'
    ];
    public function index() {
        $response = ObjectResponse::CorrectResponse();
        try {
            $referencias = DB::table('referencias')->select('*')
            ->where('baja','<>','*')
            ->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Referencias');
            data_set($response, 'data', $referencias);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function indexBaja() {
        $response = ObjectResponse::CorrectResponse();
        try {
            $referencias = DB::table('referencias')->select('*')
            ->where('baja','=','*')
            ->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Referencias Borradas');
            data_set($response, 'data', $referencias);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function siguiente() {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Referencia::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Referencia');
            data_set($response, 'alert_text', 'Siguiente Referencia');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function save(Request $request) {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        $referencia = new Referencia();
        $referencia->numero=$request->numero;
        $referencia->referencia=$request->referencia;
        $referencia->descripcion=$request->descripcion;
        $referencia->baja = $request->baja ?? "";
        $referencia->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Referencia registrada.');
        data_set($response, 'alert_icon', 'success');
        data_set($response, 'data', $referencia);
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
        $referencia = Referencia::find($request->numero);
        if(!$referencia){
            $response = ObjectResponse::CatchResponse('Referencia no encontrada');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        $referencia->numero=$request->numero;
        $referencia->referencia=$request->referencia;
        $referencia->descripcion=$request->descripcion;
        $referencia->baja = $request->baja ?? "";
        $referencia->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Referencia actualizada.');
        data_set($response, 'alert_icon', 'success');
        data_set($response, 'data', $referencia);
        return response()->json($response, $response['status_code']);
    }
}   
