<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\FacturasFormato;
use Illuminate\Support\Facades\Validator;
class FacturasFormatoController extends Controller
{
    //   protected   $messages=[
    //     'required' => 'El campo :attribute es obligatorio.',
    //     'max' => 'El campo :attribute no puede tener más de :max caracteres.',
    //     'unique' => 'El campo :attribute ya ha sido registrado',
    //     ];
    //     protected $rules = [
    //     'numero_forma'=>'required|integer',
    //     'numero_dato'=>'required|integer',
    //     'forma_renglon'=>'required|numeric',
    //     'forma_columna'=>'required|numeric',
    //     'forma_renglon_dos'=>'required|numeric',
    //     'forma_columna_dos'=>'required|numeric',
    //     'numero_archivo'=>'required|integer',
    //     'nombre_campo'=>'required|string|max:35',
    //     'longitud'=>'required|numeric',
    //     'tipo_campo'=>'required|integer',
    //     'descripcion_campo'=>'required|string|max:240',
    //     'formato'=>'required|integer',
    //     'cuenta'=>'required|integer',
    //     'funcion'=>'required|integer',
    //     'naturaleza'=>'required|integer',
    //     'tiempo_operacion'=>'required|integer',
    //     'renglon_impresion'=>'required|integer',
    //     'columna_impresion'=>'required|integer',
    //     'font_nombre'=>'required|string|max:20',
    //     'font_tamaño'=>'required|integer',
    //     'font_bold'=>'required|string|max:1',
    //     'font_italic'=>'required|string|max:1',
    //     'font_subrayado'=>'required|string|max:1',
    //     'font_rallado'=>'required|string|max:1',
    //     'visible'=>'required|string|max:1',
    //     'importe_transaccion'=>'required|numeric',
    // ];
      public function index($id){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $formatos = FacturasFormato::where("Numero_Forma",'=',$id)->get();
            
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro');
            data_set($response,'data',$formatos);
            // dd($response['data']);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        // dd($response);
        return response()->json($response,$response["status_code"]);
    }
}
