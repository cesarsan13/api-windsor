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

    protected $rulesPropietario = [
        'nombre' => 'required|string|max:50',
        'clave_seguridad' => 'required|string|max:10',
        'busqueda_max' => 'required|integer',
        'con_recibos' => 'required|integer',
        'con_facturas' => 'required|integer',
        'clave_bonificacion' => 'required|string|max:10',
    ];

    protected $rulesConfiguracion = [
        'numero_configuracion' => 'required|integer',
        'descripcion_configuracion' => 'required|string|max:50',
        'valor_configuracion' => 'required|integer',
        'texto_configuracion' => 'nullable|string|max:70'
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

    public function updatePropietario(Request $request){
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rulesPropietario, $this->messages);
        if($validator->fails()){
           $alert_text = implode(" ", $validator->messages()->all());
           $response = ObjectResponse::BadResponse($alert_text);
           data_set($response, 'alert_icon', 'error');
           data_set($response, 'message', 'Informacion no valida');
           return response()->json($response, $response['status_code']);
        }

        try{
            Propietario::where('numero', 1)
            ->update([
                "nombre" => $request->nombre,
                "clave_seguridad" => $request->clave_seguridad,
                "busqueda_max" => $request->busqueda_max,
                "con_recibos" => $request->con_recibos,
                "con_facturas" => $request->con_facturas, 
                "clave_bonificacion" => $request->clave_bonificacion,
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Propietario actualizado');
            data_set($response, 'alert_text', 'Propietario actualizado');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }


    public function updateConfiguracion(Request $request){
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rulesConfiguracion, $this->messages);
        if($validator->fails()){
           $alert_text = implode(" ", $validator->messages()->all());
           $response = ObjectResponse::BadResponse($alert_text);
           data_set($response, 'alert_icon', 'error');
           data_set($response, 'message', 'Informacion no valida');
           return response()->json($response, $response['status_code']);
        }

        try{
            Configuracion::where('numero_configuracion', $request->numero_configuracion)
            ->update([
                "numero_configuracion" => $request->numero_configuracion,
                "descripcion_configuracion" => $request->descripcion_configuracion,
                "valor_configuracion" => $request->valor_configuracion,
                "texto_configuracion" => $request->texto_configuracion ?? ' '
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Configuracion actualizada');
            data_set($response, 'alert_text', 'Configuracion actualizada');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    public function siguienteConfiguracion()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Configuracion::max('numero_configuracion');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Configuracion');
            data_set($response, 'alert_text', 'Siguiente Configuracion');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function NuevaConfiguracion(Request $request){
        try{
            $validator = Validator::make($request->all(), $this->rulesConfiguracion, $this->messages);
            if($validator->fails()){
               $alert_text = implode(" ", $validator->messages()->all());
               $response = ObjectResponse::BadResponse($alert_text);
               data_set($response, 'alert_icon', 'error');
               data_set($response, 'message', 'Informacion no valida');
               return response()->json($response, $response['status_code']);
            }
            $datosFiltrados = $request->only([
                'numero_configuracion',
                'descripcion_configuracion',
                'valor_configuracion',
                'texto_configuracion',
            ]);
            $nuevo = Configuracion::create([
                "numero_configuracion" => $datosFiltrados['numero_configuracion'],
                "descripcion_configuracion" => $datosFiltrados['descripcion_configuracion'],
                "valor_configuracion" => $datosFiltrados['valor_configuracion'],
                "texto_configuracion" => $datosFiltrados['texto_configuracion']?? ' ',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Configuracion registrada');
            data_set($response, 'alert_text', 'Configuracion registrada');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

}