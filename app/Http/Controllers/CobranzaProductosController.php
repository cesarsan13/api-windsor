<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\TrabRepCobr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CobranzaProductosController extends Controller
{
    public function infoDetallePedido($fecha1, $fecha2, $articulo = '', $artFin = '')
    {
        DB::table('trab_rep_cobr')->delete();
        $Tsql = DB::table('detalle_pedido')
            ->join('alumnos', 'detalle_pedido.articulo', '=', 'alumnos.id')
            ->whereBetween('fecha', [$fecha1, $fecha2]);
        if (trim($articulo) !== '' || trim($artFin) !== '') {
            $Tsql->whereBetween('articulo', [$articulo, $artFin]);
        }
        $Tsql->select('detalle_pedido.*', 'alumnos.nombre')->get();
        $result = $Tsql->get();        
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $result);
        return response()->json($response, $response['status_code']);
    }
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
    public function insertTrabRepCobr(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        $datosFiltrados = $request->only([
            'recibo',
            'fecha',
            'articulo',
            'documento',
            'alumno',
            'nombre',
            'importe'
        ]);
        $nuevoRegistro = TrabRepCobr::create([
            'recibo'=>$datosFiltrados['recibo'],
            'fecha'=>$datosFiltrados['fecha'],
            'articulo'=>$datosFiltrados['articulo'],
            'documento'=>$datosFiltrados['documento'],
            'alumno'=>$datosFiltrados['alumno'],
            'nombre'=>$datosFiltrados['nombre'],
            'importe'=>$datosFiltrados['importe']
        ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
        return response()->json($response, $response['status_code']);
    }
    public function infoTrabRepCobr($porNombre=0) {
        $Tsql = DB::table('trab_rep_cobr');
        if ($porNombre===1){
            $Tsql->orderBy('articulo')->orderBy('nombre')->get();
        }else{
            $Tsql->orderBy('articulo')->orderBy('alumno')->get();
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Horarios');
        data_set($response, 'data', $Tsql);
        return response()->json($response, $response['status_code']);
    }
}
