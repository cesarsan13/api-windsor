<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use Illuminate\Support\Facades\DB;

class CobranzaController extends Controller
{
    public function PDF($Fecha_Inicial, $Fecha_Final, $cajero = null)
    {
        $response = ObjectResponse::DefaultResponse();

        $queryCajero = DB::table('cobranza_diaria')
        ->join('cajeros', 'cobranza_diaria.cajero', '=', 'cajeros.numero')
            ->whereBetween('fecha_cobro', [$Fecha_Inicial, $Fecha_Final])
            ->select('cobranza_diaria.*','cajeros.nombre');
        if ($cajero !== null && $cajero > 0) {
            $queryCajero->where('cajero', $cajero);
        }
        $resultCajero = $queryCajero->orderBy('cajero')->get();

        $queryProducto = DB::table('detalle_pedido')
            ->join('productos', 'detalle_pedido.articulo', '=', 'productos.id')
            ->whereBetween('detalle_pedido.fecha', [$Fecha_Inicial, $Fecha_Final])
            ->orderBy('detalle_pedido.articulo')
            ->select('detalle_pedido.*', 'productos.descripcion')  // Selecciona las columnas necesarias
            ->get();

        $resultProducto = $queryProducto;



        $queryTipoPago = DB::table('cobranza_diaria')
            ->join('tipo_cobro AS tipo_pago_1', 'cobranza_diaria.tipo_pago_1', '=', 'tipo_pago_1.id')
            ->leftJoin('tipo_cobro AS tipo_pago_2', 'cobranza_diaria.tipo_pago_2', '=', 'tipo_pago_2.id') // Usa leftJoin si tipo_pago_2 puede ser nulo
            ->whereBetween('cobranza_diaria.fecha_cobro', [$Fecha_Inicial, $Fecha_Final])
            ->select('cobranza_diaria.*', 'tipo_pago_1.descripcion AS descripcion1', 'tipo_pago_2.descripcion AS descripcion2');

        if ($cajero !== null && $cajero > 0) {
            $queryTipoPago->where('cobranza_diaria.cajero', $cajero);
        }

        $resultTipoPago = $queryTipoPago->get();


        $data = [
            "cajeros" => $resultCajero,
            "producto" => $resultProducto,
            "tipo_pago" => $resultTipoPago,
        ];
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $data);
        return response()->json($response, $response['status_code']);
    }
}
