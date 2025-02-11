<?php

namespace App\Http\Controllers;

use App\Models\Ejecutables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ObjectResponse;

class EjecutablesController extends Controller
{
    protected $rules = [
        'ruta_archivo' => 'required|string',
    ];
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
    ];
    public function index()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $ejecutables =  Ejecutables::where('baja', '<>', '*')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $ejecutables);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function indexBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $ejecutables =  Ejecutables::where('baja', '=', '*')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $ejecutables);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }

    public function siguiente()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Ejecutables::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Ejecutable');
            data_set($response, 'alert_text', 'Siguiente Ejecutable');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function alta(Request $request)
    {
        try {

            $ultimo_ejec = $this->siguiente();
            $nuevo_ejec = intval($ultimo_ejec->getData()->data) + 1;
            $request->merge(['numero' => $nuevo_ejec]);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            $response = ObjectResponse::DefaultResponse();
            if ($validator->fails()) {
                $alert_text = implode("<br>", $validator->messages()->all());
                $response = ObjectResponse::BadResponse($alert_text);
                data_set($response, 'message', 'Informacion no valida');
                data_set($response, 'alert_icon', 'error');
                return response()->json($response, $response['status_code']);
            }
            $datosFiltrados = $request->only([
                'numero',
                'descripcion',
                'ruta_archivo',
                'icono',
            ]);
            $nuevoCobro = Ejecutables::create([
                "numero" => $datosFiltrados['numero'],
                "descripcion" => $datosFiltrados['descripcion'],
                "ruta_archivo" => $datosFiltrados['ruta_archivo'] ?? '',
                "icono" => $datosFiltrados['icono'] ?? '',
                "baja" => $datosFiltrados['baja'] ?? '',

            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Ejecutable registrado.');
            data_set($response, 'alert_text', 'Ejecutable registrado');
            data_set($response, 'alert_icon', 'success');
            data_set($response, 'data', $request->numero);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    public function update(Request $request, Ejecutables $ejecutables)
    {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'alert_icon', 'error');
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        try {
            $tipo_cobro = Ejecutables::where('numero', $request->numero)
                ->update([
                    "descripcion" => $request->descripcion,
                    "ruta_archivo" => $request->ruta_archivo ?? '',
                    "icono" => $request->icono ?? '',
                    "baja" => $request->baja ?? '',
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Ejecutable actualizado');
            data_set($response, 'alert_text', 'Ejecutable actualizado');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Ejecutable');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }


    public function storeExe(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        exec($request->ruta_archivo, $output, $return_var);
        if ($return_var === 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Archivo ejecutado correctamente',
                'output' => $output
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un error al ejecutar el archivo',
                'output' => $output
            ]);
        }
    }
}
