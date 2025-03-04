<?php

namespace App\Http\Controllers;

use App\Models\AccesosMenu;
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
            $sub_menus = SubMenus::select(
                'sub_menus.numero',
                'sub_menus.id_acceso',
                'sub_menus.descripcion',
                'accesos_menu.descripcion as menu_descripcion',
                'accesos_menu.menu',
                'accesos_menu.ruta as menu_ruta',
                'sub_menus.baja',
            )
                ->leftJoin('accesos_menu', 'accesos_menu.numero', '=', 'sub_menus.id_acceso')
                ->where('sub_menus.baja', '<>', '*')
                ->orderBy('sub_menus.numero', 'ASC')
                ->orderBy('sub_menus.id_acceso', 'ASC')
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
            $sub_menus = SubMenus::select(
                'sub_menus.numero',
                'sub_menus.id_acceso',
                'sub_menus.descripcion',
                'accesos_menu.descripcion as menu_descripcion',
                'accesos_menu.menu',
                'accesos_menu.ruta as menu_ruta',
                'sub_menus.baja',
            )
                ->leftJoin('accesos_menu', 'accesos_menu.numero', '=', 'sub_menus.id_acceso')
                ->where('sub_menus.baja', '=', '*')
                ->orderBy('sub_menus.numero', 'ASC')
                ->orderBy('sub_menus.id_acceso', 'ASC')
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
            $lastId = SubMenus::max('numero') ?? 0;
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
        $exist = SubMenus::where('numero', $lastId)
            ->where('id_acceso', $request->id_acceso)
            ->first();
        if ($exist) {
            $response = ObjectResponse::BadResponse('Este submenú ya está registrado con el mismo número y acceso. Intente con un número diferente o asócielo a otro acceso.');
            data_set($response, 'message', 'Este submenú ya está registrado con el mismo número y acceso. Intente con un número diferente o asócielo a otro acceso.');
            data_set($response, 'alert_icon', 'warning');
            return response()->json($response, $response['status_code']);
        }
        try {
            $sub_menu = SubMenus::create([
                'numero' => $lastId,
                'id_acceso' => $request->id_acceso,
                'descripcion' => $request->descripcion,
                'baja' => $request->baja
            ]);
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Información no válida.');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        try {
            $originalSubMenu = SubMenus::where('numero', $request->numero)
                ->where('id_acceso', $request->id_acceso)
                ->first();
            $nuevoNumero = $this->siguiente($request->descripcion);
            if ($originalSubMenu) {
                if ($originalSubMenu->numero != $nuevoNumero) {
                    SubMenus::where('numero', $originalSubMenu->numero)
                        ->where('id_acceso', $originalSubMenu->id_acceso)
                        ->delete();
                    SubMenus::create([
                        'numero' => $nuevoNumero,
                        'id_acceso' => $request->id_acceso,
                        'descripcion' => $request->descripcion,
                        'baja' => $request->baja,
                    ]);
                } else {
                    $originalSubMenu->where('numero', $originalSubMenu->numero)
                        ->where('id_acceso', $originalSubMenu->id_acceso)
                        ->update([
                            'descripcion' => $request->descripcion,
                            'baja' => $request->baja,
                        ]);
                }
            } else {
                SubMenus::create([
                    'numero' => $nuevoNumero,
                    'id_acceso' => $request->id_acceso,
                    'descripcion' => $request->descripcion,
                    'baja' => $request->baja,
                ]);
            }
            $data = [
                'numero' => $nuevoNumero,
                'id_acceso' => $request->id_acceso
            ];
            data_set($response, 'message', 'Submenú actualizado exitosamente.');
            data_set($response, 'data', $data);
        } catch (\Exception $ex) {
            Log::error("Error al actualizar Submenú", ["ERROR" => $ex->getMessage()]);
            $response = ObjectResponse::CatchResponse("Hubo un error al actualizar el submenú.");
        }
        return response()->json($response, $response["status_code"]);
    }
}
