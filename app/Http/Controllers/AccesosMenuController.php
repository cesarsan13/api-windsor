<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ObjectResponse;
use App\Models\AccesosMenu;
use Illuminate\Support\Facades\Validator;

class AccesosMenuController extends Controller
{
    protected $rules = [
        'numero' => 'required|integer',
        'ruta' => 'required|string',
        'descripcion' => 'required|string|max:100',
        'icono' => 'nullable|string|max:100',
        'baja' => 'nullable|string|max:1',
    ];
    protected   $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    public function index()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $accesos = AccesosMenu::select('*')->where('baja', '<>', '*')->get();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $accesos);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function indexBaja()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $accesos = AccesosMenu::select('*')->where('baja', '=', '*')->get();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $accesos);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
    public function siguiente()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $siguiente = AccesosMenu::max('numero');
            data_set($response, 'message', 'Peticion satisfactoria | Siguiente Acceso Menu');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function save(Request $request)
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                $response = ObjectResponse::CatchResponse($validator->errors()->all());
                return response()->json($response, $response['status_code']);
            }
            $existencia = AccesosMenu::where('numero', $request->numero)->first();
            if ($existencia) {
                $response = ObjectResponse::CatchResponse('El acceso menú con el número ' . $request->numero . ' ya existe');
                data_set($response, 'alert_text', 'El acceso menú con el número ' . $request->numero . ' ya existe');
                return response()->json($response, $response['status_code']);
            }
            $acceso = new AccesosMenu();
            $acceso->numero = $request->numero;
            $acceso->ruta = $request->ruta;
            $acceso->descripcion = $request->descripcion;
            $acceso->icono = $request->icono;
            $acceso->menu = $request->menu;
            $acceso->baja = $request->baja ?? '';
            $acceso->save();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'alert_text', 'Menu Guardado');
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
            AccesosMenu::where('numero', $request->numero)
                ->update([
                    "ruta" => $request->ruta,
                    "descripcion" => $request->descripcion,
                    "icono" => $request->icono,
                    "menu" => $request->menu,
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
