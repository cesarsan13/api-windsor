<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\SubMenus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubMenusController extends Controller
{
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
}
