<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupos;
use App\Models\ObjectResponse;

class GruposController extends Controller
{
    public function index()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $grupos = Grupos::where('baja', '<>', '*')->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de grupos');
            data_set($response, 'data', $grupos);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function indexBaja()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $grupos = Grupos::where('baja', '=', '*')->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de grupos bajas');
            data_set($response, 'data', $grupos);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
}
