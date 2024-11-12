<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Clases;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClasesController extends Controller
{
    protected $rules = [
        'materia' => 'required|integer',
        'grupo' => 'required|integer',
        'profesor' => 'required|integer',
        'lunes' => 'nullable|string|max:10',
        'martes' => 'nullable|string|max:10',
        'miercoles' => 'nullable|string|max:10',
        'jueves' => 'nullable|string|max:10',
        'viernes' => 'nullable|string|max:10',
        'sabado' => 'nullable|string|max:10',
        'domingo' => 'nullable|string|max:10',
        'baja' => 'nullable|string|max:1',
    ];
    protected   $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    public function index()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $clas = Clases::select(
                'clases.*',
                DB::raw("CONCAT(profesores.nombre, ' ', profesores.ap_paterno, ' ', profesores.ap_materno) as profesor_nombre"),
                'materias.descripcion as materia_descripcion',
                'horarios.horario as grupo_descripcion'
            )
                ->leftJoin('profesores', 'clases.profesor', '=', 'profesores.numero')
                ->leftJoin('materias', 'clases.materia', '=', 'materias.numero')
                ->leftJoin('horarios', 'clases.grupo', '=', 'horarios.numero')
                ->where('clases.baja', '')
                ->get()
                ->makeHidden(['created_at', 'updated_at']);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria. Lista de roles:');
            data_set($response, 'data', $clas);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function updateClases(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $mensajes) {
                $errors[$field] = implode(', ', $mensajes);
            }
            $mensaje = "Error de validación:\n";
            foreach ($errors as $error) {
                $mensaje .= $error . "\n";
            }
            $response = ObjectResponse::BadResponse($mensaje);
            data_set($response, 'errors', $errors);
            return response()->json($response, $response['status_code']);
        }
        try {
            $clase = Clases::where("materia", $request->materia)
                ->where("grupo", $request->grupo)
                ->update([
                    "profesor" => $request->profesor,
                    "lunes" => $request->lunes ?? '',
                    "martes" => $request->martes ?? '',
                    "miercoles" => $request->miercoles ?? '',
                    "jueves" => $request->jueves ?? '',
                    "viernes" => $request->viernes ?? '',
                    "sabado" => $request->sabado ?? '',
                    "domingo" => $request->domingo ?? '',
                    "baja" => $request->baja ?? '',
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Clase actualizada');
            data_set($response, 'alert_text', 'Clase actualizada.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Clase');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }

    public function postClases(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $mensajes) {
                $errors[$field] = implode(', ', $mensajes);
            }
            $mensaje = "Error de validación:\n";
            foreach ($errors as $error) {
                $mensaje .= $error . "\n";
            }
            $response = ObjectResponse::BadResponse($mensaje);
            data_set($response, 'errors', $errors);
            return response()->json($response, $response['status_code']);
        }
        try {
            $datosFiltrados = $request->only([
                'materia',
                'grupo',
                'profesor',
                'lunes',
                'martes',
                'miercoles',
                'jueves',
                'viernes',
                'sabado',
                'domingo',
                'baja',
            ]);
            $nuevaClase = Clases::create([
                "materia" => $datosFiltrados['materia'],
                "grupo" => $datosFiltrados['grupo'],
                "profesor" => $datosFiltrados['profesor'],
                "lunes" => $datosFiltrados['lunes'] ?? '',
                "martes" => $datosFiltrados['martes'] ?? '',
                "miercoles" => $datosFiltrados['miercoles'] ?? '',
                "jueves" => $datosFiltrados['jueves'] ?? '',
                "viernes" => $datosFiltrados['viernes'] ?? '',
                "sabado" => $datosFiltrados['sabado'] ?? '',
                "domingo" => $datosFiltrados['domingo'] ?? '',
                "baja" => $datosFiltrados['baja'] ?? '',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Clase registrada.');
            data_set($response, 'alert_text', 'Clase registrada.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'alert_text', $ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function indexBaja()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $clas = Clases::select(
                'clases.*',
                DB::raw("CONCAT(profesores.nombre, ' ', profesores.ap_paterno, ' ', profesores.ap_materno) as profesor_nombre"),
                'materias.descripcion as materia_descripcion',
                'horarios.horario as grupo_descripcion'
            )
                ->leftJoin('profesores', 'clases.profesor', '=', 'profesores.numero')
                ->leftJoin('materias', 'clases.materia', '=', 'materias.numero')
                ->leftJoin('horarios', 'clases.grupo', '=', 'horarios.numero')
                ->where('clases.baja', '*')
                ->get()
                ->makeHidden(['created_at', 'updated_at']);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de Clases inactivas');
            data_set($response, 'data', $clas);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
}
