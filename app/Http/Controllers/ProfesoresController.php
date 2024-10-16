<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Profesores;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProfesoresController extends Controller
{
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
        'cp' => 'required|string|max:06',
        'pais' => 'required|string|max:50',
        'rfc' => 'required|string|max:20',
        'telefono_1' => 'required|string|max:20',
        'telefono_2' => 'required|string|max:20',
        'fax' => 'required|string|max:20',
        'celular' => 'required|string|max:20',
        'email' => 'required|string|max:80',
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
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }

        $profesor = new Profesores();
        $profesor->numero = $request->numero;
        $profesor->nombre = $request->nombre;
        $profesor->ap_paterno = $request->ap_paterno;
        $profesor->ap_materno = $request->ap_materno;
        $profesor->direccion = $request->direccion;
        $profesor->colonia = $request->colonia;
        $profesor->ciudad = $request->ciudad;
        $profesor->estado = $request->estado;
        $profesor->cp = $request->cp;
        $profesor->pais = $request->pais;
        $profesor->rfc = $request->rfc;
        $profesor->telefono_1 = $request->telefono_1;
        $profesor->telefono_2 = $request->telefono_2;
        $profesor->fax = $request->fax;
        $profesor->celular = $request->celular;
        $profesor->email = $request->email;
        $profesor->contraseña = bcrypt($request->contraseña);
        $profesor->baja = $request->baja;
        $profesor->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Profesor registrado.');
        data_set($response, 'data', $profesor);
        return response()->json($response, $response['status_code']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }

        $profesor = Profesores::find($request->numero);
        if (!$profesor) {
            $response = ObjectResponse::CatchResponse('Profesor no encontrado');
            return response()->json($response, $response['status_code']);
        }

        $profesor->nombre = $request->nombre;
        $profesor->ap_paterno = $request->ap_paterno;
        $profesor->ap_materno = $request->ap_materno;
        $profesor->direccion = $request->direccion;
        $profesor->colonia = $request->colonia;
        $profesor->ciudad = $request->ciudad;
        $profesor->estado = $request->estado;
        $profesor->cp = $request->cp;
        $profesor->pais = $request->pais;
        $profesor->rfc = $request->rfc;
        $profesor->telefono_1 = $request->telefono_1;
        $profesor->telefono_2 = $request->telefono_2;
        $profesor->fax = $request->fax;
        $profesor->celular = $request->celular;
        $profesor->email = $request->email;
        if (!empty($request->contraseña)) {
            $profesor->contraseña = bcrypt($request->contraseña);
        }
        $profesor->baja = $request->baja;
        $profesor->save();

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Profesor actualizado.');
        data_set($response, 'data', $profesor);
        return response()->json($response, $response['status_code']);
    }
}
