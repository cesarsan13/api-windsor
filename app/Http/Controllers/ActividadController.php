<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Asignaturas;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActividadController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
    ];
    protected $rules = [
        'materia' => 'required|integer',
        'secuencia' => 'required|integer',
        'descripcion' => 'required|string',
        'evaluaciones' => 'nullable|integer',
        'EB1' => 'required|integer',
        'EB2' => 'required|integer',
        'EB3' => 'required|integer',
        'EB4' => 'required|integer',
        'EB5' => 'required|integer',
        'baja' => 'nullable|string',
    ];
    public function getActividades()
    {
        $response = ObjectResponse::DefaultResponse();
        $actividad = DB::table('actividades')->where('baja', '<>', '*')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Actividades');
        data_set($response, 'data', $actividad);
        return response()->json($response, $response['status_code']);
    }
    public function getActividadesBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        $actividad = DB::table('actividades')->where('baja', '=', '*')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Actividades');
        data_set($response, 'data', $actividad);
        return response()->json($response, $response['status_code']);
    }
    public function postActividad(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'data', $validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $dataFiltrados = $request->only([
            'materia',
            'secuencia',
            'descripcion',            
            'EB1',
            'EB2',
            'EB3',
            'EB4',
            'EB5',
            'baja',
            'evaluaciones',
        ]);
        $asignatura = Asignaturas::find($dataFiltrados['materia']);
        $nuevaActividad = Actividad::create([
            'materia' => $dataFiltrados['materia'],
            'secuencia' => $dataFiltrados['secuencia'],
            'matDescripcion' => $asignatura->descripcion ?? '',
            'descripcion' => $dataFiltrados['descripcion'],
            'evaluaciones' => $dataFiltrados['evaluaciones'] ?? 0,
            'EB1' => $dataFiltrados['EB1'],
            'EB2' => $dataFiltrados['EB2'],
            'EB3' => $dataFiltrados['EB3'],
            'EB4' => $dataFiltrados['EB4'],
            'EB5' => $dataFiltrados['EB5'],
            'baja' => $dataFiltrados['baja'] ?? '',
        ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
        return response()->json($response, $response['status_code']);
    }
    public function updateActividad(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        $asignatura = Asignaturas::find($request->materia);
        $actividad = Actividad::where('materia', $request->materia)
            ->where('secuencia', $request->secuencia)
            ->update([
                'matDescripcion' => $asignatura->descripcion ?? '',
                'descripcion' => $request->descripcion,
                'evaluaciones' => $request->evaluaciones ?? 0,
                'EB1' => $request->EB1,
                'EB2' => $request->EB2,
                'EB3' => $request->EB3,
                'EB4' => $request->EB4,
                'EB5' => $request->EB5,
                'baja' => $request->baja ?? '',
            ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria : Datos Actualizados');
        data_set($response, 'data', $actividad);
        return response()->json($response, $response['status_code']);
    }
    public function ultimaSecuencia(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $siguiente = Actividad::where('materia','=', $request->materia)->max('secuencia')+1;        
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Ultima Secuencia Actividad');
        data_set($response, 'data', $siguiente);
        return response()->json($response, $response['status_code']);
    }
}
