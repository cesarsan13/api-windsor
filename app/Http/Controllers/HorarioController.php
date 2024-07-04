<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
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
    public function getHorarios()
    {
        $horario = DB::table('horarios')->where('baja', '<>', '*')->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$horario]
        ];
        return response()->json($response, 200);
    }
    public function getHorariosBaja()
    {
        $horario = DB::table('horarios')->where('baja', '=', '*')->get();
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => [$horario]
        ];
        return response()->json($response, 200);
    }
    public function postHorario(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = [
                "status_code" => 400,
                "status" => false,
                "message" => "Informacion Invalida",
                "data" => [$validator->errors()->all()]
            ];
            return response()->json($response, 400);
        }
        $datosFiltrados = $request->only([
            'numero',
            'cancha',
            'dia',
            'horario',
            'max_niños',
            'sexo',
            'edad_ini',
            'edad_fin',
            'baja'
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
            'baja' => $datosFiltrados['baja'] ?? ''
        ]);
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => []
        ];
        return response()->json($response, 200);
    }
    public function updateHorario(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = [
                "status_code" => 400,
                "status" => false,
                "message" => "Informacion Invalida",
                "data" => [$validator->errors()->all()]
            ];
            return response()->json($response, 400);
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
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => []
        ];
        return response()->json($response, 200);
    }
    public function ultimoHorario()
    {
        $siguiente = Horario::max('numero') + 1;
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "data" => $siguiente
        ];
        return response()->json($response, 200);
    }
}
