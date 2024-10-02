<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\ObjectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
    ];
    protected $rules = [
        'numero' => 'required|integer',
        'cancha' => 'required|integer',
        'dia' => 'required|max:50',
        'horario' => 'required|max:50',
        'max_niños' => 'required|integer',
        'sexo' => 'required|max:50',
        'edad_ini' => 'required|integer',
        'edad_fin' => 'required|integer',
        'baja' => 'nullable|max:1'
    ];
    public function getAlumnosXHorario()
    {
        try {
            $response = ObjectResponse::DefaultResponse();
            $tsql = "";
            $tsql .= " SELECT ";
            $tsql .= " h.numero,";
            $tsql .= " h.horario AS horario,  ";
            $tsql .= " COUNT(a.numero) AS Tot_Alumnos";
            $tsql .= " FROM ";
            $tsql .= " horarios h";
            $tsql .= " LEFT JOIN ";
            $tsql .= " alumnos a ON a.horario_1 = h.numero  ";
            $tsql .= " GROUP BY ";
            $tsql .= " h.numero, h.horario;";
            $resultados = DB::select($tsql);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
            data_set($response, 'data', $resultados);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function getHorarios()
    {
        $response = ObjectResponse::DefaultResponse();
        $horario = DB::table('horarios')->where('baja', '<>', '*')->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$horario]
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $horario);
        return response()->json($response, $response['status_code']);
    }
    public function getHorariosBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        $horario = DB::table('horarios')->where('baja', '=', '*')->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$horario]
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios inactivos');
        data_set($response, 'data', $horario);
        return response()->json($response, $response['status_code']);
    }
    public function postHorario(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        try {


            $datosFiltrados = $request->only([
                'numero',
                'cancha',
                'dia',
                'horario',
                'max_niños',
                'sexo',
                'edad_ini',
                'edad_fin',
                'baja',
            ]);
            $nuevoHorario = Horario::create([
                'numero' => $datosFiltrados['numero'],
                'cancha' => $datosFiltrados['cancha'],
                'dia' => $datosFiltrados['dia'],
                'horario' => $datosFiltrados['horario'],
                'max_niños' => $datosFiltrados['max_niños'],
                'sexo' => $datosFiltrados['sexo'],
                'edad_ini' => $datosFiltrados['edad_ini'],
                'edad_fin' => $datosFiltrados['edad_fin'],
                'baja' => $datosFiltrados['baja'] ?? '',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function updateHorario(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        $horario = Horario::where('numero', $request->numero)
            ->update([
                'cancha' => $request->cancha,
                'dia' => $request->dia,
                'horario' => $request->horario,
                'max_niños' => $request->max_niños,
                'sexo' => $request->sexo,
                'edad_ini' => $request->edad_ini,
                'edad_fin' => $request->edad_fin,
                'baja' => $request->baja ?? ''
            ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria : Datos Actualizados');
        data_set($response, 'data', $horario);
        return response()->json($response, $response['status_code']);
    }
    public function ultimoHorario()
    {
        $response = ObjectResponse::DefaultResponse();
        $siguiente = Horario::max('numero');
        $siguiente = $siguiente !== null ? $siguiente + 1 : 1;
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $siguiente);
        return response()->json($response, $response['status_code']);
    }
}
