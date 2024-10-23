<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ObjectResponse;


class UsuarioController extends Controller
{

    protected   $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    protected $rules = [
        'id' => 'required|integer',
        'name' => 'required|string|max:250',
        'nombre' => 'required|string|max:50',
        'email' => 'required|string',
        'password' => 'nullable|string',
        'numero_prop' => 'required|integer',
        'baja' => 'nullable|string|max:1',
    ];

    public function GetUsuarios()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $usuarios = User::select('id', 'name', 'nombre', 'email', 'baja') //'password',
                ->where("baja", '<>', '*')
                ->get();    
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Comentarios');
            data_set($response, 'data', $usuarios);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]); 
    }

    public function GetUsuariosBaja()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $usuarios = User::select('id', 'nombre', 'name', 'email', 'baja') //'password',
                ->where("baja", '=', '*')
                ->get();    
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion Satisfactoria | lista de Comentarios');
            data_set($response, 'data', $usuarios);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]); 
    }


    //Nuevo
    public function update(Request $request, User $user)
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $user = User::where('id', $request->id)->first();
        } catch (\Throwable $th) {
            $response = ObjectResponse::CatchResponse($th->getMessage());
            data_set($response, 'data', $th);
            return response()->json($response, $response['status_code']);
        }
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $user = User::where('id', $request->id)
                ->update([
                    "name" => $request->name,
                    "nombre" => $request->nombre,
                    "password"=>Hash::make($request->password),
                    "email" => $request->email,
                    "baja" => $request->baja ?? '',
                    "numero_prop" => $request->numero_prop,
                ]);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Usuario actualizado');
            data_set($response, 'alert_text', 'Usuario actualizado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Usuario');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }

    public function delete(Request $request, User $user)
    {
        $response = ObjectResponse::DefaultResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $user = User::where('id', $request->id)
                ->update([
                    "name" => $request->name,
                    "nombre" => $request->nombre,
                    "email" => $request->email,
                    "baja" => $request->baja,
                    "numero_prop" => $request->numero_prop,
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | usuario dado de Baja');
            data_set($response, 'alert_text', 'Usuario actualizado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Usuario');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }

    public function store(Request $request)
    {
        $ultimo_usuario = $this->siguiente();
        $nuevo_usuario = intval($ultimo_usuario->getData()->data) + 1;
        $request->merge(['id' => $nuevo_usuario]);
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $response = ObjectResponse::DefaultResponse();
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $datosFiltrados = $request->only([
                'id',
                'name',
                'nombre',
                'email',
                'password',
                'numero_prop'
            ]);
            $nuevoProveedor = User::create([
                "id" => $datosFiltrados['id'],
                "name" => $datosFiltrados['name'],
                "nombre" => $datosFiltrados['nombre'],
                "email" => $datosFiltrados['email'],
                "password" => Hash::make($datosFiltrados['password']),
                "numero_prop" => $datosFiltrados['numero_prop'],
                "baja" => $datosFiltrados['baja'] ?? '',
            ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Usuario registrado.');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    public function siguiente()
    {
        $response  = ObjectResponse::DefaultResponse();
        try {
            $siguiente = User::max('id');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Usuario');
            data_set($response, 'alert_text', 'Siguiente Usuario');
            data_set($response, 'data', $siguiente);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }

    //aqui
}