<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Comentarios;
use Illuminate\Support\Facades\Validator;
class ComentariosController extends Controller
{
        protected $messages=[
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
        ];
        protected $rules = [
            'id' => 'required|integer',
            'comentario_1' => 'required|string|max:50',
            'comentario_2' => 'required|string|max:50',
            'comentario_3' => 'required|string|max:50',
            'generales' => 'nullable|string|max:1', 
            'baja' => 'nullable|string|max:1',
            
        ];

        public function index(){
            $response = ObjectResponse::DefaultResponse();
            try{
                 $comentarios = Comentarios::where("baja",'<>','*')
                 ->get();
                 $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message','Peticion Satisfactoria | lista de Comentarios');
                data_set($response, 'data', $comentarios);

            } catch (\Excepction $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
            }
            return response()->json($response,$response["status_code"]);
        }

        public function indexBaja(){
            $response  = ObjectResponse::DefaultResponse();
            try {
                $comentarios = Comentarios::where("baja",'=','*')
                ->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response,'message','peticion satisfactoria | lista de comentarios inactivos');
                data_set($response,'data',$comentarios);
    
            } catch (\Excepction $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
            }
            return response()->json($response,$response["status_code"]);
        }

        public function siguiente(){
            $response  = ObjectResponse::DefaultResponse();
            try {
                $siguiente = Comentarios::max('id');
                $response = ObjectResponse::CorrectResponse();
                data_set($response,'message','peticion satisfactoria | Siguiente Comentario');
                data_set($response,'alert_text','Siguiente Comentario');
                data_set($response,'data',$siguiente);
    
            } catch (\Excepction $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
            }
            return response()->json($response,$response["status_code"]);
        }

        public function store(Request $request){
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            $response = ObjectResponse::DefaultResponse();
            if ($validator->fails()) {
                $response = ObjectResponse::CatchResponse($validator->errors()->all());
                return response()->json($response,$response['status_code']);
            }
            try {
                    $datosFiltrados = $request->only([
                        'id',
                        'comentario_1',
                        'comentario_2',
                        'comentario_3',
                        'generales',
                    ]);
                $nuevoCobro = Comentarios::create([
                    "id"=>$datosFiltrados['id'],
                    "comentario_1"=>$datosFiltrados['comentario_1'],
                    "comentario_2"=>$datosFiltrados['comentario_2'],
                    "comentario_3"=>$datosFiltrados['comentario_3'],
                    "generales"=>$datosFiltrados['generales'],
                    "baja"=>$datosFiltrados['baja'] ?? '',
                    
                ]);
                $response = ObjectResponse::CorrectResponse();
                data_set($response,'message','peticion satisfactoria | Comentarios registrados.');
            } catch (\Exception $ex) {
                $response = ObjectResponse::CatchResponse($ex->getMessage());
            }
            return response()->json($response,$response['status_code']);
        }

        public function update(Request $request, Comentarios $comentarios){
            $response = ObjectResponse::DefaultResponse();
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if($validator->fails()){
                $response = ObjectResponse::CatchResponse($validator->errors()->all());
                return response()->json($response,$response['status_code']);
            }
            try {
                $tipo_cobro = Comentarios::where('id',$request->id)
                    ->update(["comentario_1"=>$request->comentario_1,
                            "comentario_2"=>$request->comentario_2,
                            "comentario_3"=>$request->comentario_3,
                            "generales"=>$request->generales,
                            "baja"=>$request->baja ?? '',
                            
                            ]);
                $response = ObjectResponse::CorrectResponse();
                data_set($response,'message','peticion satisfactoria | Comentarios actualizado');
                data_set($response,'alert_text','Comentarios actualizado');
            } catch (\Exception $ex) {
                    $response = ObjectResponse::CatchResponse($ex->getMessage());
                    data_set($response, 'message', 'Peticion fallida | Actualizacion de Comentarios');
                    data_set($response, 'data', $ex);
            }
            return response()->json($response,$response['status_code']);
        }
}
