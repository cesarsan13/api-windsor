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
        'unique' => 'El campo :attribute ya ha sido registrado anteriormente',
        'integer' => 'El  campo :attribute debe ser un numero',
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
        'salon' => 'required|max:10',
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
            $tsql .= " alumnos a ON a.horario_1 = h.numero  where h.baja<>'*' ";
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
        $ultimo_horario = $this->ultimoHorario();
        $nuevo_horario = intval($ultimo_horario->getData()->data) + 1;
        $request->merge(['numero' => $nuevo_horario]);
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
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
                'salon',
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
                'salon' => $datosFiltrados['salon'],
                'baja' => $datosFiltrados['baja'] ?? '',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
            data_set($response, 'alert_text', 'Horario registrado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function updateHorario(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
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
                'salon' => $request->salon,
                'baja' => $request->baja ?? ''
            ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Horario Actualizado Correctamente');
        data_set($response, 'data', $horario);
        return response()->json($response, $response['status_code']);
    }
    public function ultimoHorario()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Horario::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Horario');
            data_set($response, 'alert_text', 'Siguiente Horario');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function storeBatchHorario(Request $request) {
        $data = $request->all();
        $validatedDataInsert = [];
        // $validatedDataUpdate = [];
        foreach ($data as $item) {
            $validated = Validator::make($item, [
                'numero' => 'required|integer',
                'cancha' => 'required|integer',
                'dia' => 'required|max:50',
                'horario' => 'required|max:50',
                'max_niños' => 'required|integer',
                'sexo' => 'required|max:50',
                'edad_ini' => 'required|integer',
                'edad_fin' => 'required|integer',
                'salon' => 'required|max:10',
                'baja' => 'nullable|max:1',
            ]);
            if ($validated->fails()) {
                Log::info($validated->messages()->all());
                continue;
            }
                $validatedDataInsert[] = $validated->validated();
        }
        if (!empty($validatedDataInsert)) {
            Horario::insert($validatedDataInsert);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Horarios insertados correctamente.');
        data_set($response, 'alert_text', 'Horario insertados.');
        return response()->json($response, $response['status_code']);
    }
}