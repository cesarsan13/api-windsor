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
            Ejecutables::create([
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
            data_set($response, 'message', 'Información no válida');
            return response()->json($response, $response['status_code']);
        }
        $rutaArchivo = escapeshellarg($request->ruta_archivo);
        if (!file_exists($request->ruta_archivo)) {
            $response = ObjectResponse::BadResponse("El archivo no existe en la ruta proporcionada: " . $request->ruta_archivo);
            data_set($response, 'message', 'El archivo no existe en la ruta proporcionada');
            return response()->json($response, $response['status_code']);
        }
        $output = [];
        $return_var = 0;
        exec("start \"\" /B " . $rutaArchivo . " > NUL 2>&1", $output, $return_var);
        if ($return_var === 0) {
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Cajero actualizado');
            data_set($response, 'alert_text', 'Cajero actualizado.');
        } else {
            $response = ObjectResponse::CatchResponse("Hubo un error al ejecutar el archivo, por favor verifique que el archivo sea ejecutable o que laravel tenga los permisos necesarios para ejecutarlo.");
            data_set($response, 'message', 'Hubo un error al ejecutar el archivo');
            data_set($response, 'codigo_error', $return_var);
            data_set($response, 'output', $output);
        }
        return response()->json($response, $response['status_code']);
    }
}
