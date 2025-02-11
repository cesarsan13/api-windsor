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
