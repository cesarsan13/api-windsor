<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ObjectResponse;
use App\Models\DocsCobranza;
use App\Models\DetallePedido;

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

    public function buscaDocumentosCobranza(Request $request)
    {
        $docCob = DB::table('documentos_cobranza')
            ->where('alumno', $request->alumno || '')
            ->where('producto', $request->producto || '')
            ->where('numero_doc', $request->numero_doc || '')
            ->first();
        if ($docCob) {
            $response = ObjectResponse::BadResponse('El documento a generar ya existe');
            data_set($response, 'errors', 'El documento a generar ya existe');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!');
        return response()->json($response, $response['status_code']);
    }

    public function guardarDocumentoCobranza(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alumno' => 'required',
            'producto' => 'required',
            'numero_doc' => 'required',
            'fecha' => 'required',
            'descuento' => 'required',
            'importe' => 'required',
        ]);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $Doc = new DocsCobranza();
        $Doc->alumno = $request->alumno;
        $Doc->producto = $request->producto;
        $Doc->numero_doc = $request->numero_doc;
        $Doc->fecha = $request->fecha;
        $Doc->descuento = $request->descuento;
        $Doc->importe = $request->importe;
        //Dejar en default ciertos campos porque no tienen
        $Doc->fecha_cobro = '';
        $Doc->importe_pago = 0;
        $Doc->ref = '';
        $Doc->grupo = '';
        $Doc->orden = 0;
        $Doc->baja = '';
        $Doc->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!, datos guardados');
        return response()->json($response, $response['status_code']);
    }

    public function buscaPropietario(Request $request)
    {
        $propietario = DB::table('propietario')
            ->where('numero', $request->numero || '')
            ->first();
        if (!$propietario) {
            $response = ObjectResponse::BadResponse('El propietario no existe');
            data_set($response, 'errors', 'El propietario no existe');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!');
        data_set($response, 'data', $propietario);
        return response()->json($response, $response['status_code']);
    }

    public function guardarDetallePedido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recibo' => 'required',
            'alumno' => 'required',
            'fecha' => 'required',
            'articulo' => 'required',
            'cantidad' => 'required',
            'precio_unitario' => 'required',
            'descuento' => 'required',
            'iva' => 'required',
            'documento' => 'required',
        ]);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $detalle = new DetallePedido();
        $detalle->recibo = $request->recibo;
        $detalle->alumno = $request->alumno;
        $detalle->fecha = $request->fecha;
        $detalle->articulo = $request->articulo;
        $detalle->cantidad = $request->cantidad;
        $detalle->precio_unitario = $request->precio_unitario;
        $detalle->descuento = $request->descuento;
        $detalle->iva = $request->iva;
        $detalle->documento = $request->documento;
        $detalle->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!, datos guardados');
        return response()->json($response, $response['status_code']);
    }
}
