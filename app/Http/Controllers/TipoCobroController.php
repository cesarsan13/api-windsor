<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\TipoCobro;
use Illuminate\Support\Facades\Validator;
class TipoCobroController extends Controller
{
    protected   $messages=[
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
        ];
        protected $rules = [
            'numero'=> 'required|integer',
            'descripcion'=>'required|string|max:50',
            'comision'=>'nullable|numeric',
            'aplicacion'=>'nullable|string|max:30',
            'cue_banco'=>'nullable|string|max:34',
            'baja'=>'nullable|string|max:1',
    ];
     public function index(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $tipos_cobro = TipoCobro::where("baja",'<>','*')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro');
            data_set($response,'data',$tipos_cobro);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        // dd($response);

        return response()->json($response,$response["status_code"]);
    }
     public function indexBaja(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $tipos_cobro = TipoCobro::where("baja",'=','*')
            ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | lista de tipos de cobro inactivos');
            data_set($response,'data',$tipos_cobro);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }
        public function siguiente(){
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = TipoCobro::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response,'message','peticion satisfactoria | Siguiente tipo de cobro');
            data_set($response,'alert_text','Siguiente tipo de cobro');
            data_set($response,'data',$siguiente);

        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response,$response["status_code"]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        
        try {
            $datosFiltrados = $request->only([
                'numero',
                'descripcion',
                'comision',
                'aplicacion',
                'cue_banco',
            ]);
            
            $datosFiltrados['comision'] = str_replace(',', '', (string)$datosFiltrados['comision']);
    
            $nuevoCobro = TipoCobro::create([
                "numero" => $datosFiltrados['numero'],
                "descripcion" => $datosFiltrados['descripcion'],
                "comision" => $datosFiltrados['comision'] ?? '',
                "aplicacion" => $datosFiltrados['aplicacion'] ?? '',
                "cue_banco" => $datosFiltrados['cue_banco'] ?? '',
                "baja" => $datosFiltrados['baja'] ?? '',
            ]);
            
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Tipo de Cobro registrado.');
            data_set($response, 'alert_text', 'Forma de Pago registrada');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        
        return response()->json($response, $response['status_code']);
    }
    
    public function update(Request $request, TipoCobro $tipo_cobro){
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }
        try {
            // Limpiar las comas de los importes
            $descripcion = $request->descripcion;
            $comision = str_replace(',', '', $request->comision);
            $aplicacion = $request->aplicacion ? str_replace(',', '', $request->aplicacion) : '';
            $cue_banco = $request->cue_banco ? str_replace(',', '', $request->cue_banco) : '';
            $baja = $request->baja ? str_replace(',', '', $request->baja) : '';
    
            $tipo_cobro = TipoCobro::where('numero', $request->numero)
                ->update([
                    "descripcion" => $descripcion,
                    "comision" => $comision,
                    "aplicacion" => $aplicacion,
                    "cue_banco" => $cue_banco,
                    "baja" => $baja,
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Tipo de cobro actualizado');
            data_set($response, 'alert_text', 'Forma de Pago actualizada');
            data_set($response, 'alert_icon', 'success');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Petición fallida | Actualización de Tipo de Cobro');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }
    
}
