<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ObjectResponse;

class Pagos1Controller extends Controller
{
    public function validarClaveCajero(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cajero' => 'required',
            'clave_cajero' => 'required',
        ]);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $cajero = $request->cajero;
        $clave_cajero = $request->clave_cajero;
        $cajero = DB::table('cajeros')
            ->where('numero',  $cajero)
            ->where('clave_cajero',  $clave_cajero)
            ->first();
        if (!$cajero) {
            $response = ObjectResponse::BadResponse('Clave incorrecta');
            data_set($response, 'errors', 'Clave incorrecta, ingrese de nuevo la clave');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Clave correcta');
        data_set($response, 'data', $cajero);
        return response()->json($response, $response['status_code']);
    }

    public function buscarArticulo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'articulo' => 'required',
        ]);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $articulo = $request->articulo;
        $producto = DB::table('productos')
            ->where('id',  $articulo)
            ->first();
        if (!$producto) {
            $response = ObjectResponse::BadResponse('Clave incorrecta');
            data_set($response, 'errors', 'Clave incorrecta, ingrese de nuevo la clave');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Clave correcta');
        data_set($response, 'data', $producto);
        return response()->json($response, $response['status_code']);
    }
}
