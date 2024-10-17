<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'El campo "Nombre" es obligatorio',
            'password.required' => 'El campo "Contraseña" es obligatorio',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)
            ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = ObjectResponse::CatchResponse("Credenciales incorrectas");
            return response()->json($response, 404);
        }
        $token = $user->createToken($request->email, ['user'])->plainTextToken;
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | usuario logueado');
        data_set($response, 'token', $token);
        data_set($response, 'data', $user);
        return response()->json($response, $response['status_code']);
    }

    public function GetUsuarios()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $usuarios = User::select('id', 'nombre', 'name', 'email', 'password', 'baja')
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
            $usuarios = User::select('id', 'nombre', 'name', 'email', 'password', 'baja')
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
            $user = User::where('id', $request->id)
                ->first();
            // dd($user);
            if (!$user || !Hash::check($request->password, $user->password)) {
                $response = ObjectResponse::CatchResponse("Contraseña de usuario inválida");
                return response()->json($response, $response['status_code']);
            }
        } catch (\Throwable $th) {
            dd("tr", $th);
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
                    "email" => $request->email,
                    // "password"=>Hash::make($request->password),
                    "baja" => $request->baja ?? '',
                    "consultorio_id" => $request->consultorio_id,
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | usuario actualizado');
            data_set($response, 'alert_text', 'Usuario actualizado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Usuario');
            data_set($response, 'data', $ex);
        }
        return response()->json($response, $response['status_code']);
    }

    public function validatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
        ]);

        if ($validator->fails()) {
            $alert_text = "Ingrese bien los datos, no estas ingresando completamente todos los campos (no campos vacios).";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }

        $user = Auth::user();
        Log::info($user->password);
        if (!Hash::check($request->current_password, $user->password)) {
            $alert_text = "La contraseña ingresada no es la correcta, asegurese de ingresar la correcta";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'La contraseña actual es incorrecta');
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria. la contraseña es correcta');
        return response()->json($response, $response['status_code']);
    }

    public function updatePasswordAndData(Request $request, $id)
    {
        $rules = [
            'new_password' => 'required',
            'confirm_new_password' => 'required|min:6',
            // 'name' => 'required',
            // 'email' => 'required|email',
        ];
        $messages = [
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'confirm_new_password.required' => 'La confirmación de la nueva contraseña es obligatoria.',
            'confirm_new_password.min' => 'La confirmación de la nueva contraseña debe tener al menos 6 caracteres.',
            // 'name.required' => 'El nombre es obligatorio.',
            // 'email.required' => 'El correo electrónico es obligatorio.',
            // 'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $alert_text = implode(" ", $errors);
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Información no válida');
            return response()->json($response, $response['status_code']);
        }
        if ($request->new_password === $request->confirm_new_password) {
            $user = User::find($id);
            if ($request->revoke_tokens) {
                $user->tokens()->delete();
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria. la contraseña es correcta');
            return response()->json($response, $response['status_code']);
        } else {
            $alert_text = "Ingrese bien los datos, las contraseñas no coinciden";
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
    }
    //aqui

}
