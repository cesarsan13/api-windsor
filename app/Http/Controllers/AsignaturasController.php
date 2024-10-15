<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\Asignaturas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AsignaturasController extends Controller
{
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
    protected $rules = [
        'numero' => 'required|numeric',
        'descripcion' => 'required|string',
    ];

    public function subjectFilter($type, $value)
    {
        switch ($type) {
            case 'numero':
                $asignaturas = DB::table('asignaturas')->where('numero', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('numero', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $asignaturas);
                return response()->json($response, $response['status_code']);
                break;
            case 'descripcion':
                $asignaturas = DB::table('asignaturas')->where('descripcion', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('descripcion', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $asignaturas);
                return response()->json($response, $response['status_code']);
                break;
            case 'nothing':
                $asignaturas = DB::table('asignaturas')->where('baja', '<>', '*')->orderBy('numero', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $asignaturas);
                return response()->json($response, $response['status_code']);
                break;
        }
    }
    public function showSubject()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $asignaturas = DB::table('asignaturas')->where('baja', '<>', '*')->orderBy('numero', 'ASC')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $asignaturas);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function lastSubject()
    {
        $maxId = DB::table('asignaturas')->max('numero');
        $response = ObjectResponse::CorrectResponse();
        Log::info($maxId);
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $maxId);
        return response()->json($response, $response['status_code']);
    }
    public function storeSubject(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                $response = ObjectResponse::BadResponse('Error de validacion' . $validator->errors(),'Error de validacion');
                data_set($response, 'errors', $validator->errors());
                return response()->json($response, $response['status_code']);
            }
            $asignatura = Asignaturas::find($request->numero);
            if ($asignatura) {
                $response = ObjectResponse::BadResponse('La asignatura ya existe','Registro ya existente');
                data_set($response, 'errors', ['numero' => ['Asignatura ya existe']]);
                return response()->json($response, $response['status_code']);
            }
            if($request->area == 1 || $request->area == 4){
                if(!$request->actividad && $request->evaluaciones == 0){
                    $response = ObjectResponse::BadResponse('No de Evaluaciones debe ser mayor a 0','Error de validacion');
                    data_set($response, 'errors', ['numero' => ['No de Evaluaciones debe ser mayor a 0']]);
                    return response()->json($response, $response['status_code']);
                }
            }
            $asignatura = new Asignaturas();
            $asignatura->numero = $request->numero;
            $asignatura->descripcion = strtoupper($request->descripcion);
            $asignatura->fecha_seg = "";
            $asignatura->hora_seg = "";
            $asignatura->cve_seg = "";
            $asignatura->baja = "";
            $asignatura->evaluaciones = $request->evaluaciones;
            if($request->actividad){
                $asignatura->actividad =  "Si";
            }else{
                $asignatura->actividad =  "No";
            }
            $asignatura->area = $request->area;
            $asignatura->orden = $request->orden;
            $asignatura->lenguaje = $request->lenguaje;
            $asignatura->caso_evaluar = $request->caso_evaluar;
            $asignatura->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Asignatura registrada.');
            data_set($response, 'alert_text', 'Asignatura registrada');
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function updateSubject(Request $request,$numero)
    {
        try {
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                $response = ObjectResponse::BadResponse('Error de validacion' . $validator->errors(),'Error de validacion');
                data_set($response, 'errors', $validator->errors());
                return response()->json($response, $response['status_code']);
            }
            if($request->area == 1 || $request->area == 4){
                if($request->actividad == false && $request->evaluaciones == 0){
                    $response = ObjectResponse::BadResponse('No de Evaluaciones debe ser mayor a 0','Error de validacion');
                    data_set($response, 'errors', ['numero' => ['No de Evaluaciones debe ser mayor a 0']]);
                    return response()->json($response, $response['status_code']);
                }
            }
            $asignatura = Asignaturas::find($numero);
            if (!$asignatura) {
                $response = ObjectResponse::BadResponse($validator->errors());
                data_set($response, 'errors', ['numero' => ['Asignatura no encontrada']]);
                return response()->json($response, $response['status_code']);
            }
            $asignatura->descripcion = strtoupper($request->descripcion);
            $asignatura->fecha_seg = "";
            $asignatura->hora_seg = "";
            $asignatura->cve_seg = "";
            if($request->baja=="n"){
                $request->baja=="";
            }
            $asignatura->baja =  $request->baja;
            $asignatura->evaluaciones = $request->evaluaciones;
            if($request->actividad){
                $asignatura->actividad =  "Si";
            }else{
                $asignatura->actividad =  "No";
            }
            $asignatura->area = $request->area;
            $asignatura->orden = $request->orden;
            $asignatura->lenguaje = $request->lenguaje;
            $asignatura->caso_evaluar = $request->caso_evaluar;
            $asignatura->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Asignatura registrada.');
            data_set($response, 'alert_text', 'Asignatura registrada');
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function bajaSubject()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $asignatura = DB::table('asignaturas')->where('baja', '=', '*')->orderBy('descripcion', 'ASC')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $asignatura);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
}
