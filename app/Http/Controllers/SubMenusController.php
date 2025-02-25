<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\SubMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubMenusController extends Controller
{
    protected $rules = [
        'id_acceso' => 'required|integer',
        'descripcion' => 'required|string',
    ];
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'integer' => 'El campo :attribute debe ser un número entero.',
        'string' => 'El campo :attribute debe ser una cadena de texto.',
    ];
    public function index()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $sub_menus = SubMenus::where('baja', '<>', '*')
                ->get();
            data_set($response, 'message', 'Petición satisfactoria.');
            data_set($response, 'data', $sub_menus);
        } catch (\Throwable $ex) {
            Log::error("Error en index de SubMenusController", [
                "ERROR" => $ex->getMessage(),
            ]);
            $response = ObjectResponse::CatchResponse(
                "Algo salió mal, por favor contacte con un administrador."
            );
        }
        return response()->json($response, $response["status_code"]);
    }

    public function baja()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $sub_menus = SubMenus::where('baja', '=', '*')
                ->leftJoin('acceso_usuarios as au', 'id_acceso', '=', 'au.id_punto_menu')
                ->get();
            data_set($response, 'message', 'Peticion Satisfactoria');
            data_set($response, 'data', $sub_menus);
        } catch (\Exception $ex) {
            Log::info("Error index en SubMenusController", ["ERROR" => $ex->getMessage()]);
            $response = ObjectResponse::CatchResponse("Algo salio mal, por favor contacte con un administrador.");
        }
        return response()->json($response, $response["status_code"]);
    }

    public function siguiente($descripcion)
    {
        $lastId = SubMenus::where('descripcion', 'like', '%' . $descripcion . '%')
            ->max('numero');
        if ($lastId == null) {
            $lastId = SubMenus::max('numero');
            $lastId += 1;
        }
        return $lastId;
    }

    public function store(Request $request)
    {
        $response = ObjectResponse::CorrectResponse();
        $lastId = $this->siguiente($request->descripcion);
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        try {
            $sub_menu = new SubMenus();
            $sub_menu->numero = $lastId;
            $sub_menu->id_acceso = $request->id_acceso;
            $sub_menu->descripcion = $request->descripcion;
            $sub_menu->baja = $request->baja;
            $sub_menu->save();
            data_set($response, 'data', $sub_menu);
            data_set($response, 'message', 'Submenu creado exitosamente.');
        } catch (\Exception $ex) {
            Log::error("Error al crear Submenu", ["ERROR" => $ex->getMessage()]);
            $response = ObjectResponse::CatchResponse("Hubo un error al crear el submenu.");
        }
        return response()->json($response, $response["status_code"]);
    }

    public function update(Request $request)
    {
        $response = ObjectResponse::CorrectResponse();
        $lastId = $this->siguiente($request->descripcion);
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        try {
            $exists =  SubMenus::where('numero', $lastId)
                ->where('id_acceso', $request->id_acceso)->first();
            if ($exists) {
                SubMenus::where('numero', $lastId)->where('id_acceso', $request->id_acceso)
                    ->update([
                        'numero' => $lastId,
                        'id_acceso' => $request->id_acceso,
                        'descripcion' => $request->descripcion,
                        'baja' => $request->baja,
                    ]);
            } else {
                $this->store($request);
            }
            $data = [
                'numero' => $lastId,
                'id_acceso' => $request->id_acceso
            ];
            data_set($response, 'message', 'Sub Menu actualizado exitosamente.');
            data_set($response, 'data', $data);
        } catch (\Exception $ex) {
            Log::error("Error al actualizar Submenu", ["ERROR" => $ex->getMessage()]);
            $response = ObjectResponse::CatchResponse("Hubo un error al actualizar el submenu.");
        }
        return response()->json($response, $response["status_code"]);
    }
}
