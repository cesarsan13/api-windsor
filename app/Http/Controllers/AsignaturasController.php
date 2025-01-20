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
    public function showSubjectCasoEvaluarOtro() 
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $asignaturas = DB::table('asignaturas')->where('baja', '<>', '*')->where('caso_evaluar','=','OTRO')->orderBy('numero', 'ASC')->get();
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
            $response = $this->lastSubject();
            $nuevo_id = $response->getData()->data;
            $request->merge(['numero' => $nuevo_id + 1]);

            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                $alert_text = implode("<br>", $validator->messages()->all());
                $response = ObjectResponse::BadResponse($alert_text);
                data_set($response, 'message', 'Informacion no valida');
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
            data_set($response, 'data', $request->numero);
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
                $alert_text = implode("<br>", $validator->messages()->all());
                $response = ObjectResponse::BadResponse($alert_text);
                data_set($response, 'message', 'Informacion no valida');
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
            data_set($response, 'message', 'Petición satisfactoria | Asignatura actualizada.');
            data_set($response, 'alert_text', 'Asignatura actualizada');
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

    public function storeBatchAsignatura(Request $request){
        $data = $request->all();
        $validatedDataInsert = [];
        $validatedDataUpdate = [];

        foreach($data as $item){
            $validated = Validator::make($item, [
                'numero' => 'required|numeric',
                'descripcion' => 'required|string|max:100',
                //'fecha_seg' => 'nullable|string|max:10',
                //'hora_seg' => 'nullable|string|max:10',
                //'cve_seg' => 'nullable|string|max:10',
                'baja' => 'nullable|string|max:1',
                'evaluaciones' => 'required|integer',
                'actividad' => 'required|string|max:10',
                'area' => 'required|integer',
                'orden' => 'required|integer',
                'lenguaje' => 'required|string|max:15',
                'caso_evaluar' => 'required|string|max:15',
            ]);

            if($validated->fails()){
                Log::info($validated->messages()->all());
                continue;
            }

            $exists = Asignaturas::where('numero', '=', $item['numero'])->exists();
            if (!$exists) {
                $validatedDataInsert[] = $validated->validated();
            } else {
                $validatedDataUpdate[] = $validated->validated();
            }
        }

        if(!empty($validatedDataInsert)){
            Asignaturas::insert($validatedDataInsert);
        }

        if(!empty($validatedDataUpdate)){
            foreach($validatedDataUpdate as $updateItem){
                Asignaturas::where('numero', $updateItem['numero'])->update($updateItem);
            }
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Asignaturas insertadas correctamente.');
        data_set($response, 'alert_text', 'Asignaturas insertadas.');
        return response()->json($response, $response['status_code']);
    }
}
