<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
    ];
    protected $rules = [
        'name' => 'required|string|max:250',
        'nombre'=>'required|string|max:50',
        'email' => 'required|email|max:40|unique:users',
        'password' => 'required|string|min:6',
        'numero_prop' => 'required|integer',
        'baja' => 'nullable|string|max:1',
    ];

    public function Register(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $usuario = DB::table('users')->max('id');
        $usuario = is_null($usuario) ? 1 : $usuario + 1;

        $validatorUsuario = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validatorUsuario->fails()) {
            $response = ObjectResponse::CatchResponse($validatorUsuario->errors()->all());
            data_set($response, 'alert_text', $validatorUsuario->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        try {
            $datosFiltradosUsuario = $request->only([
                'name',
                'email',
                'nombre',
                'numero_prop',
                'baja'
            ]);

            $passwordgenerate = Str::random(10);

            $nuevoUsuario = User::create([
                "id" => $usuario,
                "name" => $datosFiltradosUsuario['name'],
                "nombre" => $datosFiltradosUsuario['nombre'],
                "email" => $datosFiltradosUsuario['email'],
                "password" => Hash::make($passwordgenerate),
                "numero_prop" => 1,
                "baja" => '',
            ]);

            $mailData = [
                'title' => `Bienvenido ${$datosFiltradosUsuario['nombre']}`,
                'subject' => "Generación de Contraseña",
                'body' => `Estamado/a <b>${$datosFiltradosUsuario['nombre']}</b>, su contraseña para el inicio de sesion es la siguiente: <b>${$paswordgenerate}</b>`,
                'styles' => "<style>h1{color: #333;font-size:30px} p{color:#777;font-size:15px} body{font-family: Arial, sans-serif;text-align: center;background-color: #f9f9f9;} div{ max-width: 600px;margin: 0 auto;padding: 20px;background-color: #ffffff;border-radius: 10px;box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);}</style>",
            ];

            Mail::to($request->email)->send(new DemoMail($mailData));

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Usuario registrado.');
            data_set($response, 'alert_text', 'Usuario registrado exitosamente');
            
        } catch (\Exception $e) {
            $response = ObjectResponse::CatchResponse($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

}
