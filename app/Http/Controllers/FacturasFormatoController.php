<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\FacturasFormato;
use Illuminate\Support\Facades\Validator;
class FacturasFormatoController extends Controller
{
      protected   $messages=[
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
        ];
        protected $rules = [
        'numero_forma'=>'required|integer',
        'numero_dato'=>'required|integer',
        'forma_renglon'=>'required|numeric',
        'forma_columna'=>'required|numeric',
        'forma_renglon_dos'=>'required|numeric',
        'forma_columna_dos'=>'required|numeric',
        'numero_archivo'=>'required|integer',
        'nombre_campo'=>'string|max:35',
        'longitud'=>'required|numeric',
        'tipo_campo'=>'required|integer',
        'descripcion_campo'=>'string|max:240',
        'formato'=>'required|integer',
        'cuenta'=>'required|integer',
        'funcion'=>'required|integer',
        'naturaleza'=>'required|integer',
        'tiempo_operacion'=>'required|integer',
        'renglon_impresion'=>'required|integer',
        'columna_impresion'=>'required|integer',
        'font_nombre'=>'required|string|max:20',
        'font_tamaño'=>'required|integer',
        'font_bold'=>'required|string|max:1',
        'font_italic'=>'required|string|max:1',
        'font_subrallado'=>'required|string|max:1',
        'font_rallado'=>'required|string|max:1',
        'visible'=>'required|string|max:1',
        'importe_transaccion'=>'required|numeric',
    ];
      public function index($id){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $formatos = FacturasFormato::where("Numero_Forma",'=',$id)->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro');
            data_set($response,'data',$formatos);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
    public function updateFormato(Request $request,FacturasFormato $factuas_formato){
        $datos= $request->input('datos');
        try {
            foreach ($datos as $value ) {
                FacturasFormato::updateOrCreate(['numero_forma' => $value["numero_forma"], 'numero_dato' => $value["numero_dato"]],[
                'forma_renglon'=> $value["forma_renglon"],
                'forma_columna'=> $value["forma_columna"],
                'forma_renglon_dos'=> $value["forma_renglon_dos"],
                'forma_columna_dos'=> $value["forma_columna_dos"],
                'numero_archivo'=> $value["numero_archivo"],
                'nombre_campo'=> $value["nombre_campo"]??'',
                'longitud'=> $value["longitud"],
                'tipo_campo'=> $value["tipo_campo"],
                'descripcion_campo'=> $value["descripcion_campo"]??'',
                'formato'=> $value["formato"],
                'cuenta'=> $value["cuenta"],
                'funcion'=> $value["funcion"],
                'naturaleza'=> $value["naturaleza"],
                'tiempo_operacion'=> $value["tiempo_operacion"],
                'renglon_impresion'=> $value["renglon_impresion"],
                'columna_impresion'=> $value["columna_impresion"],
                'font_nombre'=> $value["font_nombre"],
                'font_tamaño'=> $value["font_tamaño"],
                'font_bold'=> $value["font_bold"]??'',
                'font_italic'=> $value["font_italic"]??'',
                'font_subrallado'=> $value["font_subrallado"]??'',
                'font_rallado'=> $value["font_rallado"]??'',
                'visible'=> $value["visible"]??'',
                'importe_transaccion'=> $value["importe_transaccion"]
                ]);
                
             }
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | formato actualizado');

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
}
