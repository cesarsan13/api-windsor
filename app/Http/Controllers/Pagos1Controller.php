<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\ObjectResponse;
use App\Models\DocsCobranza;
use App\Models\DetallePedido;
use App\Models\CobranzaDiaria;
use App\Models\Encab_Pedido;
use App\Models\Propietario;
use Illuminate\Support\Carbon;

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
            ->where('numero',  $articulo)
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
        $Tw_Base_Iva = 0;
        // $validator = Validator::make($request->all(), [
        //     'recibo' => 'required',
        //     'alumno' => 'required',
        //     'fecha' => 'required',
        //     'articulo' => 'required',
        //     'cantidad' => 'required',
        //     'precio_unitario' => 'required',
        //     'descuento' => 'required',
        //     'documento' => 'required',
        //     'total_general' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     $response = ObjectResponse::CatchResponse($validator->errors()->all());
        //     return response()->json($response, $response['status_code']);
        // }

        $producto = DB::table('productos')
            ->where('numero', '=', $request->articulo)
            ->first();

        $iva = $producto->iva;
        if ($iva) {
            $Tw_Base_Iva =  $iva;
        } else {
            $Tw_Base_Iva =  0;
        }

        $detalleExistente = DetallePedido::where('recibo', $request->recibo)
            ->where('alumno', $request->alumno)
            ->where('articulo', $request->articulo)
            ->where('documento', $request->documento)
            ->first();
        if (!$detalleExistente) {
            $detalle = new DetallePedido();
            $detalle->recibo = $request->recibo;
            $detalle->alumno = $request->alumno;
            $detalle->fecha = $request->fecha;
            $detalle->articulo = $request->articulo;
            $detalle->cantidad = $request->cantidad;
            $detalle->precio_unitario = $request->precio_unitario;
            $detalle->descuento = $request->descuento;
            $detalle->iva = $Tw_Base_Iva;
            $detalle->documento = $request->documento;
            //agregar defaults a los campos que no tienen por defecto
            $detalle->numero_factura = 0;
            $detalle->save();
        }

        $documento = $request->documento;
        if ($documento > 0) {
            $total_general = $request->total_general;
            DB::table('documentos_cobranza')
                ->where('alumno', $request->alumno)
                ->where('producto', $request->articulo)
                ->where('numero_doc', $request->numero_doc)
                ->update([
                    'fecha_cobro' => $request->fecha,
                    'importe_pago' => DB::raw('importe_pago + ' . $total_general)
                ]);
        }

        $doc_cob = DB::table('documentos_cobranza')
            ->where('producto', '=', $request->articulo)
            ->where('numero_doc', '=', $request->numero_doc)
            ->where('alumno', '=', $request->alumno)
            ->first();

        if ($doc_cob) {
            $descuento = $request->descuento;
            $descuentoP = $doc_cob->descuento;
            if ($descuentoP != $descuento) {
                DB::table('documentos_cobranza')
                    ->where('alumno', $request->alumno)
                    ->where('producto', $request->articulo)
                    ->where('numero_doc', $request->numero_doc)
                    ->update([
                        'descuento' => $request->descuento,
                    ]);
            }
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!, datos guardados');
        return response()->json($response, $response['status_code']);
    }

    public function guardaEcabYCobrD(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'recibo' => 'required',
        //     'alumno' => 'required',
        //     'fecha' => 'required',
        //     'cajero' => 'required',
        //     'total_neto' => 'required',
        //     'n_banco' => 'required',
        //     'imp_pago' => 'required',
        //     'referencia_1' => 'required',
        //     'n_banco_2' => 'required',
        //     'imp_pago_2' => 'required',
        //     'referencia_2' => 'required',
        //     'quien_paga' => 'required',
        //     'comenta' => 'required',
        //     'comentario_ad' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     $response = ObjectResponse::CatchResponse($validator->errors()->all());
        //     return response()->json($response, $response['status_code']);
        // }

        $encab_pedido = new Encab_Pedido();
        $encab_pedido->recibo = $request->recibo;
        $encab_pedido->alumno = $request->alumno;
        $encab_pedido->fecha = $request->fecha;
        $encab_pedido->cajero = $request->cajero;
        $encab_pedido->importe_total = $request->total_neto;
        $encab_pedido->tipo_pago_1 = $request->n_banco;
        $encab_pedido->importe_pago_1 = $request->imp_pago;
        $encab_pedido->referencia_1 = $request->referencia_1;
        $encab_pedido->tipo_pago_2 = $request->n_banco_2;
        $encab_pedido->importe_pago_2 = $request->imp_pago_2;
        $encab_pedido->referencia_2 = $request->referencia_2;
        $encab_pedido->nombre_quien = $request->quien_paga;
        $encab_pedido->comentario = $request->comenta;
        $encab_pedido->comentario_ad = $request->comentario_ad;
        $encab_pedido->save();

        $cobr_diaria = new CobranzaDiaria();
        $cobr_diaria->recibo = $request->recibo;
        $cobr_diaria->alumno = $request->alumno;
        $cobr_diaria->fecha_cobro = $request->fecha;
        $cobr_diaria->cajero = $request->cajero;
        $cobr_diaria->importe_cobro = $request->total_neto;
        $cobr_diaria->tipo_pago_1 = $request->n_banco;
        $cobr_diaria->importe_pago_1 = $request->imp_pago;
        $cobr_diaria->referencia_1 = $request->referencia_1;
        $cobr_diaria->tipo_pago_2 = $request->n_banco_2;
        $cobr_diaria->importe_pago_2 = $request->imp_pago_2;
        $cobr_diaria->referencia_2 = $request->referencia_2;
        $cobr_diaria->quien_paga = $request->quien_paga;
        $cobr_diaria->comentario = $request->comenta;
        $cobr_diaria->hora = Carbon::now()->format('H:i:s');
        $cobr_diaria->comentario_ad = $request->comentario_ad;
        $cobr_diaria->save();

        $prop = Propietario::where('numero', 1)->first();
        if ($prop) {
            $recibo = $prop->con_recibos;
            $recibo += 1;
            $prop->con_recibos = $recibo;
            $prop->save();
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', 'Exito!, datos guardados');
        return response()->json($response, $response['status_code']);
    }

    public function obtenerDocumentosCobranza(Request $request)
    {
        $documentosCobranza = DB::table('documentos_cobranza as dc')
            ->leftJoin('productos as pr', 'dc.producto', '=', 'pr.numero')
            ->select(
                'dc.*',
                'pr.descripcion as nombre_producto'
            )
            ->where('dc.alumno', $request->alumno)
            ->whereRaw('ROUND(dc.importe - dc.importe_pago, 2) > 0')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición Satisfactoria');
        data_set($response, 'alert_text', '¡Éxito! Datos obtenidos.');
        data_set($response, 'data', $documentosCobranza);
        return response()->json($response, $response['status_code']);
    }


    public function Busca_Inf_Cliente(Request $request)
    {
        $pedido = DB::table('Encab_Pedido')
            ->where('Recibo', $request->recibo)
            ->first();

        if ($pedido) {
            $detallePedido = DB::table('Detalle_Pedido')
                ->where('Recibo', $request->recibo)
                ->get();
            $fechaHoy = $pedido->fecha_recibo;
        }
    }
}
