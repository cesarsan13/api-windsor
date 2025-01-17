<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\FormFact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class FormFactController extends Controller
{
    public function index()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $formfact = FormFact::where('baja', '<>', '*')
                ->get()
                ->makeHidden(['created_at', 'updated_at']);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria. Lista de roles:');
            data_set($response, 'data', $formfact);
        } catch (\Exception $ex) {
            $response  = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function siguiente()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = FormFact::max('numero_forma');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Forma Factura');
            data_set($response, 'alert_text', 'Siguiente Forma Factura');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function UpdateFormFact(Request $request, FormFact $formFact)
    {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $formFact = FormFact::where('numero_forma', $request->numero_forma)
                ->update([
                    "nombre_forma" => $request->nombre_forma,
                    "longitud" => $request->longitud,
                    "baja" => $request->baja ?? '',
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Forma Factura actualizada');
            data_set($response, 'alert_text', 'Forma Factura actualizada');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Forma Factura');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }
    protected $rules = [
        'numero_forma' => 'required|integer',
        'nombre_forma' => 'required|string|max:50',
        'baja' => 'nullable|string|max:1',
    ];
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
    ];

    public function PostFormFact(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $datosFiltrados = $request->only([
                'numero_forma',
                'nombre_forma',
                'longitud',
                'baja',
            ]);
            $nuevoFormFact = FormFact::create([
                "numero_forma" => $datosFiltrados['numero_forma'],
                "nombre_forma" => $datosFiltrados['nombre_forma'],
                "longitud" => $datosFiltrados['longitud'],
                "baja" => $datosFiltrados['baja'] ?? '',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Forma Factura registrada.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function indexBaja()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $formFact = FormFact::where("baja", '=', '*')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | lista de Forma Facturas inactivos');
            data_set($response, 'data', $formFact);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function storeBatchFormFact(Request $request)
    {
        $data = $request->all();
        $validatedDataInsert = [];
        $validatedDataUpdate = [];
        foreach ($data as $item) {
            $validated = Validator::make($item, [
                'numero_forma' => 'required|integer',
                'nombre_forma' => 'required|string|max:50',
                'longitud' => 'required|numeric',
                'baja' => 'nullable|string|max:1',
            ]);
            if ($validated->fails()) {
                Log::info($validated->messages()->all());
                continue;
            }
            $exists = FormFact::where('numero_forma', '=', $item['numero_forma'])->exists();
            if (!$exists) {
                $validatedDataInsert[] = $validated->validated();
            } else {
                $validatedDataUpdate[] = $validated->validated();
            }
        }
        // Log::info("Datos listos", ["data" => $validatedData]);
        if (!empty($validatedDataInsert)) {
            FormFact::insert($validatedDataInsert);
        }

        if (!empty($validatedDataUpdate)) {
            foreach ($validatedDataUpdate as $updateItem) {
                FormFact::where('numero_forma', $updateItem['numero_forma'])->update($updateItem);
            }
        }
        // Log::info("que a pasao", ["resultados" => $result]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Productos insertados correctamente.');
        data_set($response, 'alert_text', 'Producto insertados.');
        return response()->json($response, $response['status_code']);
    }
}
