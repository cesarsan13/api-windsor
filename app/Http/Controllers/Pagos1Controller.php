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
use App\Models\Producto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        $docs = DB::table('documentos_cobranza')
            ->where('alumno', $request->alumno)
            ->where('producto', $request->producto)
            ->where('numero_doc', $request->numero_doc)
            ->where('fecha', $request->fecha)
            ->first();
        if ($docs) {
            $response = ObjectResponse::BadResponse('El documento ya existe');
            data_set($response, 'errors', 'El documento ya existe');
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
        data_set($response, 'data', $Doc);
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
        try{
            $Tw_Base_Iva = 0;
            foreach($request->all() as $item){    
                $validator = Validator::make($item, [
                    'recibo' => 'required',
                    'alumno' => 'required',
                    'fecha' => 'required',
                    'articulo' => 'required',
                    'cantidad' => 'required',
                    'precio_unitario' => 'required',
                    'descuento' => 'required',
                    'documento' => 'required',     
                    'iva' => 'nullable',
                    'numero_factura' => 'nullable'
                ]);

                if ($validator->fails()) {
                    $response = ObjectResponse::CatchResponse($validator->errors()->all());
                    return response()->json($response, $response['status_code']);
                }
                $producto = DB::table('productos')
                    ->where('numero', '=', (string) $item['articulo'])
                    ->first();
                
                $iva = $producto->iva;
                if ($iva) {
                    $Tw_Base_Iva =  $iva;
                } else {
                    $Tw_Base_Iva =  0;
                }
                
                $detalleExistente = DB::table('detalle_pedido')
                    ->where('recibo', '=', (int) $item['recibo'])
                    ->where('alumno', '=', (int) $item['alumno'])
                    ->where('articulo', '=', (string) $item['articulo'])
                    ->where('documento', '=', (int) $item['documento'])
                    ->first();

                $validatedDataInsert = [];
                if(!$detalleExistente){
                    $validatedDataInsert[] = [
                        'recibo' => (int) $item['recibo'],
                        'alumno' => (int) $item['alumno'],
                        'fecha' => (string) $item['fecha'],
                        'articulo' => (string) $item['articulo'],
                        'cantidad' => (int) $item['cantidad'],
                        'precio_unitario' => (int) $item['precio_unitario'],
                        'descuento' => (int) $item['descuento'],
                        'iva' =>  $Tw_Base_Iva,
                        'documento' => (int) $item['documento'],
                        'numero_factura' => 0, // Valor por defecto
                    ];
                }
                
                if (!empty($validatedDataInsert)) {
                    DetallePedido::insert($validatedDataInsert);
                }

                $documento = $item['documento'];
                if ($documento > 0) {
                    $total_general = (float)$item['total_general'];
                    DB::table('documentos_cobranza')
                        ->where('alumno', (int) $item['alumno'])
                        ->where('producto', (string) $item['articulo'])
                        ->where('numero_doc',(int) $item['documento'])
                        ->update([
                            'fecha_cobro' => (string) $item['fecha'],
                            'importe_pago' => DB::raw('COALESCE(importe_pago, 0) + ' . $total_general)
                        ]);
                }

                $doc_cob = DB::table('documentos_cobranza')
                    ->where('producto', '=', (string) $item['articulo'])
                    ->where('numero_doc', '=', (int) $item['documento'])
                    ->where('alumno', '=', (int) $item['alumno'])
                    ->first();

                if ($doc_cob) {
                    $descuento = (int) $item['descuento'];
                    $descuentoP = (int)  $doc_cob['descuento'];
                    if ($descuentoP != $descuento) {
                        DB::table('documentos_cobranza')
                            ->where('alumno', (int) $item['alumno'])
                            ->where('producto', (string)  $item['articulo'])
                            ->where('numero_doc', (int) $item['documento'])
                            ->update([
                                'descuento' => (int) $item['descuento'],
                            ]);
                    }
                }
            }

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición Satisfactoria');
            data_set($response, 'alert_text', 'Exito!, datos guardados');
            data_set($response, 'data', $validatedDataInsert);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            data_set($response, 'message', 'Lista de Detalle Pedido no se insertaron.');
            data_set($response, 'alert_text', 'Producto no insertado.');
            return response()->json($response, $response['status_code']);
        }
    }

    //Esta parte se encarga de generar el pago
    public function guardaEcabYCobrD(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'recibo' => 'required',
                'alumno' => 'required',
                'fecha' => 'required',
                'articulo' => 'required',
                'cajero' => 'required',
                'total_neto' => 'required',
                'n_banco' => 'required',
                'imp_pago' => 'required',
                'referencia_1' => 'nullable',
                'n_banco_2' => 'nullable',
                'imp_pago_2' => 'nullable',
                'referencia_2' => 'nullable',
                'quien_paga' => 'nullable',
                'comenta' => 'nullable',
                'comentario_ad' => 'nullable',
            ]);

            if ($validator->fails()) {
                $response = ObjectResponse::CatchResponse($validator->errors()->all());
                return response()->json($response, $response['status_code']);
            }

            $encab_pedido = new Encab_Pedido();
            $encab_pedido->recibo = (int) $request->recibo;
            $encab_pedido->alumno = (int) $request->alumno;
            $encab_pedido->fecha = (string) $request->fecha;
            $encab_pedido->cajero = (int) $request->cajero;
            $encab_pedido->importe_total = (int) $request->total_neto;
            $encab_pedido->tipo_pago_1 = (int) $request->n_banco;
            $encab_pedido->importe_pago_1 = (int) $request->imp_pago;
            $encab_pedido->referencia_1 = (string) $request->referencia_1 ?? null;
            $encab_pedido->tipo_pago_2 =  (int) $request->n_banco_2 ?? null;
            $encab_pedido->importe_pago_2 = (int) $request->imp_pago_2 ?? null;
            $encab_pedido->referencia_2 = (string) $request->referencia_2 ?? null;
            $encab_pedido->nombre_quien = (string) $request->quien_paga ?? null;
            $encab_pedido->comentario = (string) $request->comenta ?? null;
            $encab_pedido->comentario_ad = (string) $request->comentario_ad ?? null;
            $encab_pedido->save();

            $cobr_diaria = new CobranzaDiaria();
            $cobr_diaria->recibo = (int) $request->recibo;
            $cobr_diaria->alumno = (int) $request->alumno;
            $cobr_diaria->fecha_cobro = (string) $request->fecha;           
            $cobr_diaria->cajero = (int) $request->cajero;
            $cobr_diaria->importe_cobro = (int) $request->total_neto;
            $cobr_diaria->tipo_pago_1 = (int) $request->n_banco;
            $cobr_diaria->importe_pago_1 =  (int) $request->imp_pago;
            $cobr_diaria->referencia_1 = (string) $request->referencia_1 ?? null;
            $cobr_diaria->tipo_pago_2 = (int) $request->n_banco_2 ?? null;
            $cobr_diaria->importe_pago_2 = (int) $request->imp_pago_2 ?? null;
            $cobr_diaria->referencia_2 = (string) $request->referencia_2 ?? null;
            $cobr_diaria->quien_paga = (int) $request->quien_paga ?? null;  //aqui se manda como int, pero lo recibe la api como string hay que ver eso 
            $cobr_diaria->comentario = (string) $request->comenta ?? null;
            $cobr_diaria->hora = Carbon::now()->format('H:i:s');
            $cobr_diaria->comentario_ad = (string) $request->comentario_ad ?? null; //aqui se manda como string, pero lo recibe la api como int hay que ver eso 
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
        
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
            data_set($response, 'message', 'No se inserto el Pago');
            data_set($response, 'alert_text', $e->getMessage());
            return response()->json($response, $response['status_code']);
        }
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
            ->orderBy('dc.numero_doc', 'ASC')
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

    //Empieza el cambio
    public function storeBatchDetallePedido(Request $request)
    {
        $data = $request->all();
        $validatedDataInsert = [];
        $Tw_Base_Iva = 0;

        foreach($data as $item){    
            $validator = Validator::make($item, [
                 'recibo' => 'required',
                 'alumno' => 'required',
                 'articulo' => 'required',
                 'documento' => 'required',
                 'fecha' => 'required',
                 'cantidad' => 'required',
                 'precio_unitario' => 'required',
                 'descuento' => 'required',
                 'iva' => 'required',
                 'numero_factura' => 'required'
             ]);
             
             if ($validator->fails()) {
                continue;
             }

            $validated = $validator->validated();
            //Verifica que el producto existas, y asigna el iva 
            $producto = Producto::where('numero', '=', $validated['articulo'])->first();
            
            $Tw_Base_Iva = $producto->iva ?? 0;
            
            //Verifica si existe ya el registro
            $detalleExistente = DetallePedido::where('recibo', $validated['recibo'])
                ->where('alumno', $validated['alumno'])
                ->where('articulo', $validated['articulo'])
                ->where('documento', $validated['documento'])
                ->first();
             
            if(!$detalleExistente){
                $validatedDataInsert[] = [
                    'recibo' => $validated['recibo'],
                    'alumno' => $validated['alumno'],
                    'fecha' => $validated['fecha'],
                    'articulo' => $validated['articulo'],
                    'cantidad' => $validated['cantidad'],
                    'precio_unitario' => $validated['precio_unitario'],
                    'descuento' => $validated['descuento'],
                    'iva' => $Tw_Base_Iva,
                    'documento' => $validated['documento'],
                    'numero_factura' => $validated['numero_factura'], // Valor por defecto
                ];
            }
        }

        if (!empty($validatedDataInsert)) {
            DetallePedido::insert($validatedDataInsert);
        }

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Detalle Pedido insertados correctamente.');
        data_set($response, 'alert_text', 'Producto insertados.');
        return response()->json($response, $response['status_code']);
    }

}
