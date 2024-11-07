<?php

namespace App\Http\Controllers;

use App\Models\CobranzaDiaria;
use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CobranzaController extends Controller
{
    public function PDF(Request $request)
    {
        // dd($request);
        $Fecha_Inicial = $request->input('Fecha_Inicial');
        $Fecha_Final = $request->input('Fecha_Final');

        $cajero = $request->input('cajero');
        $response = ObjectResponse::DefaultResponse();

        $queryCajero = DB::table('cobranza_diaria')
            ->join('cajeros', 'cobranza_diaria.cajero', '=', 'cajeros.numero')
            ->whereBetween('fecha_cobro', [$Fecha_Inicial, $Fecha_Final])
            ->select('cobranza_diaria.*', 'cajeros.nombre');
        if ($cajero !== null && $cajero > 0) {
            $queryCajero->where('cajero', $cajero);
        }
        $resultCajero = $queryCajero->orderBy('cajero')->get();

        $queryProducto = DB::table('detalle_pedido')
            ->join('productos', 'detalle_pedido.articulo', '=', 'productos.numero')
            ->whereBetween('detalle_pedido.fecha', [$Fecha_Inicial, $Fecha_Final])
            ->orderBy('detalle_pedido.articulo')
            ->select('detalle_pedido.*', 'productos.descripcion');  // Selecciona las columnas necesarias

        $resultProducto = $queryProducto->get();



        $queryTipoPago = DB::table('cobranza_diaria')
            ->join('tipo_cobro AS tipo_pago_1', 'cobranza_diaria.tipo_pago_1', '=', 'tipo_pago_1.numero')
            ->leftJoin('tipo_cobro AS tipo_pago_2', 'cobranza_diaria.tipo_pago_2', '=', 'tipo_pago_2.numero') // Usa leftJoin si tipo_pago_2 puede ser nulo
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
    public function getCobranza(Request $request)
    {
        $rules = [
            'fecha' => 'required',
            'cheque' => 'nullable|boolean',
            'recibo' => 'nullable|integer',
            'alumno' => 'nullable|integer'
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no puede tener más de :max caracteres.',
            'min' => 'El campo :attribute debe tener al menos :min caracteres.',
            'unique' => 'El  :attribute ya ha sido registrado anteriormente',
            'numeric' => 'El campo :attribute debe ser un número decimal.',
            'string' => 'El campo :attribute debe ser una cadena.',
            'integer' => 'El campo :attribute debe ser un número.',
            'boolean' => 'El campo :attribute debe ser un valor booleano.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validacion' . $validator->errors(), 'Error de validacion');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::DefaultResponse();
        $cobranzaDiaria = CobranzaDiaria::where('fecha_cobro', '=', $request->fecha);
        if ($request->cheque) {
            $cobranzaDiaria->where(function ($query) {
                $query->where('tipo_pago_1', '=', '2')
                    ->orWhere('tipo_pago_2', '=', '2')
                    ->orWhere('tipo_pago_1', '=', '3')
                    ->orWhere('tipo_pago_2', '=', '3')
                    ->orWhere('tipo_pago_1', '=', '4')
                    ->orWhere('tipo_pago_2', '=', '4');
            });
        }
        if ($request->recibo > 0) {
            $cobranzaDiaria->where('recibo', $request->recibo);
        }
        if ($request->alumno > 0) {
            $cobranzaDiaria->where('alumno', $request->alumno);
        }
        $cobranzaDiaria->join('alumnos', 'cobranza_diaria.alumno', '=', 'alumnos.numero');
        $cobranzaDiaria->select('cobranza_diaria.*', 'alumnos.nombre');
        $result = $cobranzaDiaria->get();
        Log::info($result);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $result);
        data_set($response, 'message', 'Petición satisfactoria | Cobranza Diaria.');
        return response()->json($response, $response['status_code']);
    }
    public function updateCobranza(Request $request)
    {
        $cobranza = CobranzaDiaria::where('recibo', $request->recibo)
            ->update([
                "cue_banco" => $request->cue_banco,
                "referencia" => $request->referencia,
                "importe" => $request->importe
            ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Cobranza Diaria actualizada');
        data_set($response, 'alert_text', 'Cobranza Diaria actualizado');
        return response()->json($response, $response['status_code']);
    }
}
