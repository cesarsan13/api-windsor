<?php

namespace App\Http\Controllers;

use App\Models\Aplicacion1;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Aplicacion1Controller extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    protected $rules = [
        'numero' => 'required|integer',
        'numero_cuenta' => 'required|string|max:35',
        'cargo_abono' => 'required|string|max:1',
        'importe_movimiento' => 'required|numeric',
        'referencia' => 'nullable|string|max:11',
        'fecha_referencia' => 'nullable|string|max:11',
    ];
    public function index()
    {
        $response = ObjectResponse::DefaultResponse();
        $aplicacion1 = Aplicacion1::select('*')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion Satisfactoria | lista de Aplicaciones 1');
        data_set($response, 'data', $aplicacion1);
        return response()->json($response, $response["status_code"]);
    }
    public function siguiente()
    {
        $response = ObjectResponse::DefaultResponse();
        $siguiente = Aplicacion1::max('numero');
        $siguiente += 1;
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Siguiente Aplicacion 1');
        data_set($response, 'alert_text', 'Siguiente aplicacion 1');
        data_set($response, 'data', $siguiente);
        return response()->json($response, $response["status_code"]);
    }
    public function postAplicacion1(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $datosFiltrados = $request->only([
            'numero',
            'numero_cuenta',
            'cargo_abono',
            'importe_movimiento',
            'referencia',
            'fecha_referencia',
        ]);
        $nuevaAplicacion = Aplicacion1::create([
            'numero' => $datosFiltrados['numero'],
            'numero_cuenta' => $datosFiltrados['numero_cuenta'],
            'cargo_abono' => $datosFiltrados['cargo_abono'],
            'importe_movimiento' => $datosFiltrados['importe_movimiento'],
            'referencia' => $datosFiltrados['referencia'] ?? '',
            'fecha_referencia' => $datosFiltrados['fecha_referencia'],
        ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Aplicacion 1 registrada.');
        return response()->json($response, $response['status_code']);
    }
    public function updateAplicacion1(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $aplicacion = Aplicacion1::where('numero', $request->numero)->first();        
        if ($request->baja === '*') {            
            $aplicacion->delete();
        } else {
            $aplicacion->update([
                'numero_cuenta' => $request->numero_cuenta,
                'cargo_abono' => $request->cargo_abono,
                'importe_movimiento' => $request->importe_movimiento,
                'referencia' => $request->referencia,
                'fecha_referencia' => $request->fecha_referencia,
            ]);
        }
        $message = $request->baja != "" ? "Aplicacion 1 eliminado" : "Aplicacion 1 actualizado";
        Log::info($message);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Aplicacion 1 actualizada');
        data_set($response, 'alert_text', $message);
        return response()->json($response, $response['status_code']);
    }
}
