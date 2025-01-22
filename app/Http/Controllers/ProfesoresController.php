<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Profesores;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\GlobalService;

class ProfesoresController extends Controller
{
    protected $validationService;
    public function __construct(GlobalService $validationService)
    {
        $this->validationService = $validationService;
    }
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
    ];
    protected $rules = [
        'numero' => 'required|integer',
        'nombre' => 'required|string|max:50',
        'ap_paterno' => 'required|string|max:50',
        'ap_materno' => 'required|string|max:50',
        'direccion' => 'required|string|max:50',
        'colonia' => 'required|string|max:50',
        'ciudad' => 'required|string|max:50',
        'estado' => 'required|string|max:20',
        'cp' => 'nullable|string|max:06',
        'pais' => 'nullable|string|max:50',
        'rfc' => 'nullable|string|max:20',
        'telefono_1' => 'nullable|string|max:20',
        'telefono_2' => 'nullable|string|max:20',
        'fax' => 'nullable|string|max:20',
        'celular' => 'nullable|string|max:20',
        'email' => 'nullable|string|max:80',
        'contraseña' => 'nullable|string|max:12',
    ];

    public function index()
    {
        $response = ObjectResponse::CorrectResponse();
        try {
            $profesores = DB::table('profesores as pf')->select(
                DB::raw("CONCAT(pf.nombre, ' ', pf.ap_paterno, ' ', pf.ap_materno) as nombre_completo"),
                'pf.*'
            )
                ->where('baja', '<>', '*')
                ->get();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Profesores');
            data_set($response, 'data', $profesores);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function indexBaja()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $profesores = DB::table('profesores as pf')->select(
                DB::raw("CONCAT(pf.nombre, ' ', pf.ap_paterno, ' ', pf.ap_materno) as nombre_completo"),
                'pf.*'
            )
                ->where('baja', '=', '*')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Profesores Borrados');
            data_set($response, 'data', $profesores);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function siguiente()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = Profesores::max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Profesor');
            data_set($response, 'alert_text', 'Siguiente Profesor');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }

        $profesor = new Profesores();
        $profesor->numero = $request->numero;
        $profesor->nombre = $request->nombre ?? "";
        $profesor->ap_paterno = $request->ap_paterno ?? "";
        $profesor->ap_materno = $request->ap_materno ?? "";
        $profesor->direccion = $request->direccion ?? "";
        $profesor->colonia = $request->colonia ?? "";
        $profesor->ciudad = $request->ciudad ?? "";
        $profesor->estado = $request->estado ?? "";
        $profesor->cp = $request->cp ?? "";
        $profesor->pais = $request->pais ?? "";
        $profesor->rfc = $request->rfc ?? "";
        $profesor->telefono_1 = $request->telefono_1 ?? "";
        $profesor->telefono_2 = $request->telefono_2 ?? "";
        $profesor->fax = $request->fax ?? "";
        $profesor->celular = $request->celular ?? "";
        $profesor->email = $request->email ?? "";
        $profesor->contraseña = bcrypt($request->contraseña ?? "");
        $profesor->baja = $request->baja ?? "";
        $profesor->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Profesor registrado.');
        data_set($response, 'alert_icon', 'success');
        data_set($response, 'data', $profesor);
        return response()->json($response, $response['status_code']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }

        $profesor = Profesores::find($request->numero);
        if (!$profesor) {
            $response = ObjectResponse::CatchResponse('Profesor no encontrado');
            data_set($response, 'alert_icon', 'error');
            return response()->json($response, $response['status_code']);
        }

        $profesor->nombre = $request->nombre ?? "";
        $profesor->ap_paterno = $request->ap_paterno ?? "";
        $profesor->ap_materno = $request->ap_materno ?? "";
        $profesor->direccion = $request->direccion ?? "";
        $profesor->colonia = $request->colonia ?? "";
        $profesor->ciudad = $request->ciudad ?? "";
        $profesor->estado = $request->estado ?? "";
        $profesor->cp = $request->cp ?? "";
        $profesor->pais = $request->pais ?? "";
        $profesor->rfc = $request->rfc ?? "";
        $profesor->telefono_1 = $request->telefono_1 ?? "";
        $profesor->telefono_2 = $request->telefono_2 ?? "";
        $profesor->fax = $request->fax ?? "";
        $profesor->celular = $request->celular ?? "";
        $profesor->email = $request->email ?? "";
        if (!empty($request->contraseña)) {
            $profesor->contraseña = bcrypt($request->contraseña ?? "");
        }
        $profesor->baja = $request->baja ?? "";
        $profesor->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Profesor actualizado.');
        data_set($response, 'data', $profesor);
        data_set($response, 'alert_icon', 'success');
        return response()->json($response, $response['status_code']);
    }

    public function storeBatchProfesores (Request $request){
        $data = $request->all();
        $validatedDataInsert = [];
        $alert_text = "";
        $validatedDataUpdate = [];
        $this->validationService->validateAndProcessData(
            "numero",
            $data,
            $this->rules,
            $this->messages,
            $alert_text,
            Profesores::class,
            $validatedDataInsert,
            $validatedDataUpdate
        );

        if(!empty($validatedDataInsert)){
            foreach($validatedDataInsert as &$insertItem){
                if(isset($insertItem['contraseña'])){
                    $insertItem['contraseña'] = bcrypt($insertItem['contraseña']);
                    
                }
            }
            Profesores::insert($validatedDataInsert);
        }
        if(!empty($validatedDataUpdate)){
            foreach($validatedDataUpdate as &$updateItem){
                if(isset($updateItem['contraseña'])){
                    $updateItem['contraseña'] = bcrypt($updateItem['contraseña']);
                }
            }
            Profesores::where('numero', $updateItem['numero'])->update($updateItem);
        }

        if($alert_text){
            $response = ObjectResponse::BadResponse($alert_text);
        } else {
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Lista de Profesores insertados correctamente.');
            data_set($response, 'alert_text', 'Todos los Profesores se insertaron correctamente.');
        }
        return response()->json($response, $response['status_code']);
    }
}
