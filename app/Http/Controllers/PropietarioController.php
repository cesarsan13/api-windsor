<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Propietario;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Validator;

class PropietarioController extends Controller {
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
        'numeric' => 'El campo :attribute debe ser un número decimal.',
        'string' => 'El campo :attribute debe ser una cadena.',
        'integer' => 'El campo :attribute debe ser un número.',
        'boolean' => 'El campo :attribute debe ser un valor booleano.',
    ];

    public function getPropietario(){
        $response = ObjectResponse::DefaultResponse();
        try{
            $Propietario = Propietario::where("numero", "=", 1 )-> get();
            $response = ObjectResponse::CorrectResponse();

            data_set($response, 'message', 'Peticion Satisfactoria | Impresion del Propietario');
            data_set($response, 'data', $Propietario);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function getConfiguracion(){
        $response = ObjectResponse::DefaultResponse();
        try{
            $configuracion = Configuracion::orderBy('numero_configuracion', 'ASC')->get();
            $response = ObjectResponse::CorrectResponse();

            data_set($response, 'message', 'Peticion Satisfactoria | Lista de Configuraciones');
            data_set($response, 'data', $configuracion);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

}