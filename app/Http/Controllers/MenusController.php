<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Menus;
use Illuminate\Support\Facades\Validator;

class MenusController extends Controller
{
    protected $rules = [
        'numero' => 'required|integer',
        'nombre' => 'required|string',
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
            $menus = Menus::select('numero', 'nombre', 'baja')
                ->where('baja', '<>', '*')
                ->orderBy('nombre', 'ASC')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Menus');
            data_set($response, 'data', $menus);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function indexBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $menus = Menus::select('numero', 'nombre', 'baja')
                ->where('baja', '=', '*')
                ->orderBy('nombre', 'ASC')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Menus');
            data_set($response, 'data', $menus);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function siguiente()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Menus::max('numero');
            $siguiente += 1;
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente menu');
            data_set($response, 'alert_text', 'Siguiente menu');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function save(Request $request)
    {
        try {
            $ultimo_menu = $this->siguiente();
            $nuevo_menu = intval($ultimo_menu->getData()->data);
            $request->merge(['numero' => $nuevo_menu]);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            $response = ObjectResponse::CorrectResponse();
            if ($validator->fails()) {
                $response = ObjectResponse::CatchResponse($validator->errors()->all());
                return response()->json($response, $response['status_code']);
            }
            $existencia = Menus::where('numero', $request->numero)->first();
            if ($existencia) {
                $response = ObjectResponse::CatchResponse('El menú con el número ' . $request->numero . ' ya existe');
                data_set($response, 'alert_text', 'El menú con el número ' . $request->numero . ' ya existe');
                return response()->json($response, $response['status_code']);
            }
            $acceso = new Menus();
            $acceso->numero = $request->numero;
            $acceso->nombre = $request->nombre;
            $acceso->baja = $request->baja ?? '';
            $acceso->save();
            data_set($response, 'message', 'Peticion satisfactoria | Menu registrado.');
            data_set($response, 'alert_text', 'Menu Guardado');
            data_set($response, 'alert_icon', 'success');
            data_set($response, 'data', $request->numero);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    public function update(Request $request)
    {
        $response = ObjectResponse::CorrectResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            Menus::where('numero', $request->numero)
                ->update([
                    "nombre" => $request->nombre,
                    "baja" => $request->baja ?? '',
                ]);
            data_set($response, 'message', 'Petición satisfactoria');
            data_set($response, 'alert_text', 'Menú actualizado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Petición fallida | Actualización de Menú');
            data_set($response, 'data', $ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
}
