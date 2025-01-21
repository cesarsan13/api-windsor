<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Comentarios;
use Illuminate\Support\Facades\Validator;

class ComentariosController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    protected $rules = [
        'numero' => 'required|integer',
        'comentario_1' => 'required|string|max:50',
        'comentario_2' => 'nullable|string|max:50',
        'comentario_3' => 'nullable|string|max:50',
        'generales' => 'nullable|integer|max:1',
        'baja' => 'nullable|string|max:1',

    ];

    public function index()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $comentarios = Comentarios::where("baja", '<>', '*')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Comentarios');
            data_set($response, 'data', $comentarios);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function indexBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $comentarios = Comentarios::where("baja", '=', '*')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de comentarios inactivos');
            data_set($response, 'data', $comentarios);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function siguiente()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Comentarios::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Comentario');
            data_set($response, 'alert_text', 'Siguiente Comentario');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function store(Request $request)
    {
        try {

            $ultimo_comentario = $this->siguiente();
            $nuevo_comentario = intval($ultimo_comentario->getData()->data) + 1;
            $request->merge(['numero' => $nuevo_comentario]);
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
                'comentario_1',
                'comentario_2',
                'comentario_3',
                'generales',
            ]);
            $nuevoCobro = Comentarios::create([
                "numero" => $datosFiltrados['numero'],
                "comentario_1" => $datosFiltrados['comentario_1'],
                "comentario_2" => $datosFiltrados['comentario_2'] ?? '',
                "comentario_3" => $datosFiltrados['comentario_3'] ?? '',
                "generales" => $datosFiltrados['generales'],
                "baja" => $datosFiltrados['baja'] ?? '',

            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Comentario registrado.');
            data_set($response, 'alert_text', 'Comentario registrado');
            data_set($response, 'alert_icon', 'success');
            data_set($response, 'data', $request->numero);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    public function update(Request $request, Comentarios $comentarios)
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
            $tipo_cobro = Comentarios::where('numero', $request->numero)
                ->update([
                    "comentario_1" => $request->comentario_1,
                    "comentario_2" => $request->comentario_2 ?? '',
                    "comentario_3" => $request->comentario_3 ?? '',
                    "generales" => $request->generales,
                    "baja" => $request->baja ?? '',

                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Comentario actualizado');
            data_set($response, 'alert_text', 'Comentario actualizado');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Comentario');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }

    public function storeBatchComentarios (Request $request){
        $data = $request->all();
        $validatedDataInsert = [];
        $validatedDataUpdate = [];

        foreach($data as $item){
            $validated = Validator::make($item,[
                'numero' => 'required|integer',
                'comentario_1' => 'required|string|max:50',
                'comentario_2' => 'nullable|string|max:50',
                'comentario_3' => 'nullable|string|max:50',
                'generales' => 'nullable|integer|max:1',
                'baja' => 'nullable|string|max:1',
            ]);

            if ($validated->fails()) {
                Log::info($validated->messages()->all());
                continue;
            }

            $exists = Comentarios::where('numero', '=', $item['numero'])->exists();

            if (!$exists) {
                $validatedDataInsert[] = $validated->validated();
            } else {
                $validatedDataUpdate[] = $validated->validated();
            }
        }

        if(!empty($validatedDataInsert)){
            Comentarios::insert($validatedDataInsert);
        }

        if(!empty($validatedDataUpdate)){
            foreach ($validatedDataUpdate as $updateItem) {
                Comentarios::where('numero', $updateItem['numero'])->update($updateItem);
            }
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Comentarios insertados correctamente.');
        data_set($response, 'alert_text', 'Comentarios insertados.');
        return response()->json($response, $response['status_code']);
    }
}
