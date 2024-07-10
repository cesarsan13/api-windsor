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
    public function login(Request $request){
          $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
        ], [
            'name.required' => 'El campo "Nombre" es obligatorio',
            'password.required' => 'El campo "Contraseña" es obligatorio',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        $user =  User::where('name', $request->name)
            ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            $response = ObjectResponse::CatchResponse("Credenciales incorrectas");
            return response()->json($response, 404);
        }
        $token = $user->createToken($request->name, ['user'])->plainTextToken;
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | usuario logueado');
        data_set($response, 'token', $token);
        data_set($response, 'data', $user);
        return response()->json($response, $response['status_code']);
    }
}
