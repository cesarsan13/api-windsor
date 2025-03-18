<?php

namespace App\Http\Controllers;

use App\Models\DocsCobranza;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActCobranzaController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
    ];
    
    protected $rules =[
        'alumno'=>'required|integer',
        'producto'=>'required|string',
        'numero_doc'=>'required|integer',
        'fecha'=>'required|string',
        'importe'=>'required|numeric',
        'descuento'=>'required|numeric'
    ];

    public function getDocumentosAlumno(Request $request) {
        $response = ObjectResponse::DefaultResponse();
        $documentoAlumno = DB::table('documentos_cobranza')
        ->join('productos', DB::raw('CAST(documentos_cobranza.producto AS CHAR)'), '=', DB::raw('CAST(productos.numero AS CHAR)'))
        ->select('documentos_cobranza.producto','productos.descripcion','documentos_cobranza.numero_doc','documentos_cobranza.fecha','documentos_cobranza.importe','documentos_cobranza.descuento','documentos_cobranza.fecha_cobro','documentos_cobranza.importe_pago')
        ->where('alumno','=',$request->alumno)->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | lista de Documentos Alumnos');
        data_set($response, 'data', $documentoAlumno);
        return response()->json($response, $response['status_code']);
    }

    public function postActCobranza(Request $request) {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        $sql = DB::table('documentos_cobranza')
        ->where('alumno',$request->alumno)
        ->where('producto',$request->producto)
        ->where('numero_doc',$request->numero_doc)
        ->where('fecha',$request->fecha)
        ->first();
        if($sql){
            $alert_text = "El registro ya ha sido registrado anteriormente.";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'errors', $validator->errors());
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        $datosFiltrados = $request->only([
            'alumno',
            'producto',
            'numero_doc',
            'fecha',
            'importe',
            'descuento'
        ]);
        $nuevoActCobranza = DocsCobranza::create([
            'alumno'=>$datosFiltrados['alumno'],
            'producto'=>$datosFiltrados['producto'],
            'numero_doc'=>$datosFiltrados['numero_doc'],
            'fecha'=>$datosFiltrados['fecha'],
            'importe'=>$datosFiltrados['importe'],
            'descuento'=>$datosFiltrados['descuento']
        ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria : Datos insertados correctamente');
        return response()->json($response, $response['status_code']);
    }

    public function updateActCobranza(Request $request) {
        $response = ObjectResponse::CorrectResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Información no válida.');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        try {
            DocsCobranza::where('alumno', $request->alumno)
            ->where('producto', $request->producto)
            ->where('numero_doc', $request->numero_doc)
            ->where('fecha', $request->fecha)
            ->update([
                'fecha' => $request->fecha,
                'importe' => $request->importe,
                'descuento' => $request->descuento
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria : Datos Actualizados');
            data_set($response, 'alert_text', 'Datos Actualizados');
            return response()->json($response, $response['status_code']);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'alert_text', 'Error en actualizar los datos');
            return response()->json($response, $response['status_code']);
        }
    }

    public function deleteActCobranza(Request $request) {
        $response = ObjectResponse::DefaultResponse();
        $actCobranza = DocsCobranza::where('alumno',$request->alumno)
        ->where('producto',$request->producto)
        ->where('numero_doc',$request->numero_doc)
        ->where('fecha',$request->fecha)->delete();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria : Datos Eliminados');
        data_set($response, 'data', $actCobranza);
        return response()->json($response, $response['status_code']);
    }
}
