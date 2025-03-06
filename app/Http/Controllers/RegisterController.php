<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'unique' => 'El :attribute ya ha sido registrado anteriormente.',
    ];
    protected $rules = [
        'name' => 'required|string|max:250|unique:users',
        'nombre' => 'required|string|max:50|unique:users',
        'email' => 'required|email|max:40|unique:users',
        'password' => 'required|string|min:6',
    ];

    public function Register(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $usuario = DB::table('users')->max('id');
        $usuario = is_null($usuario) ? 1 : $usuario + 1;

        $validatorUsuario = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validatorUsuario->fails()) {
            $errors = implode('<br>', $validatorUsuario->errors()->all());
            $response = ObjectResponse::CatchResponse($errors);
            data_set($response, 'alert_text', $errors);
            return response()->json($response, $response['status_code']);
        }        
        try {
            $datosFiltradosUsuario = $request->only([
                'name',
                'email',
                'nombre',
                'password'
            ]);

            $nuevoUsuario = User::create([
                "id" => $usuario,
                "name" => $datosFiltradosUsuario['name'],
                "nombre" => $datosFiltradosUsuario['nombre'],
                "email" => $datosFiltradosUsuario['email'],
                "password" => Hash::make($datosFiltradosUsuario['password']),
                "numero_prop" => 1,
                "baja" => '',
            ]);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Usuario registrado.');
            data_set($response, 'alert_text', 'Usuario registrado exitosamente.');
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }
}
