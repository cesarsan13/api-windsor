<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\TrabRepCobr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CobranzaProductosController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
    ];
    protected $rules = [
        'recibo' => 'required|integer',
        'fecha' => 'required|max:11',
        'articulo' => 'required|integer',
        'documento' => 'required|integer',
        'alumno' => 'required|integer',
        'nombre' => 'required|max:50',
        'importe' => 'required|integer'
    ];

    public static function formatFechaString($date)
    {
        if ($date === "" || $date === null || !trim($date)) {
            return "";
        }
        $dateWithoutTime = explode(' ', $date)[0];
        $fechaObj = \DateTime::createFromFormat('Y-m-d', $dateWithoutTime);
        if (!$fechaObj) {
            return "";
        }
        return $fechaObj->format('Y/m/d');
    }


    public function infoDetallePedido($fecha1, $fecha2, $articulo, $artFin)
    {
        DB::table('trab_rep_cobr')->delete();
        $fecha1 = $this->formatFechaString($fecha1);
        $fecha2 = $this->formatFechaString($fecha2);
        $Tsql = DB::table('detalle_pedido')
            ->leftJoin('alumnos', 'detalle_pedido.articulo', '=', 'alumnos.numero')
            ->whereBetween('fecha', [$fecha1, $fecha2]);
        if (trim($articulo) !== '' && trim($artFin) !== '') {
            $Tsql->whereBetween('articulo', [$articulo, $artFin]);
        }
        $result = $Tsql->select('detalle_pedido.*', 'alumnos.nombre')->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria');
        data_set($response, 'data', $result);
        return response()->json($response, $response['status_code']);
    }

    public function insertTrabRepCobr(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'error', $validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $trabrepcobr = new TrabRepCobr();
        $trabrepcobr->recibo = $request->recibo;
        $trabrepcobr->fecha = $request->fecha;
        $trabrepcobr->articulo = $request->articulo;
        $trabrepcobr->documento = $request->documento;
        $trabrepcobr->alumno = $request->alumno;
        $trabrepcobr->nombre = $request->nombre;
        $trabrepcobr->importe = $request->importe;
        $trabrepcobr->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
        return response()->json($response, $response['status_code']);
    }
    public function infoTrabRepCobr($porNombre = 0)
    {
        $porNombre = (int)$porNombre;
        $response = ObjectResponse::DefaultResponse();
        $query = DB::table('trab_rep_cobr');
        if ($porNombre === 1) {
            $query->orderBy('articulo')->orderBy('nombre');
        } else {
            $query->orderBy('articulo')->orderBy('alumno');
        }
        $query->leftJoin('productos', 'trab_rep_cobr.articulo', '=', 'productos.numero');
        $query->select('trab_rep_cobr.*', 'productos.descripcion');
        $results = $query->get();
        // dd($query);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de TrabRepCobr');
        data_set($response, 'data', $results);
        return response()->json($response, $response['status_code']);
    }
}
