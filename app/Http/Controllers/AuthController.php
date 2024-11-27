<?php

namespace App\Http\Controllers;

use App\Models\Acceso_Usuario;
use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ->where('baja', "<>", "*")
            ->first();
        $access = Acceso_Usuario::where('id_usuario', '=', $user->id)->get();
        // Log::info($access);
        $user->permissions = $access;
        // Log::info($user);
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
}
