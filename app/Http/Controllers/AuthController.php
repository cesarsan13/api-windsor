<?php

namespace App\Http\Controllers;

use App\Models\Acceso_Usuario;
use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'xescuela' => 'required',
            ], [
                'email.required' => 'El campo "Nombre" es obligatorio',
                'password.required' => 'El campo "Contraseña" es obligatorio',
                'xescuela.required' => 'Es obligatorio seleccionar una escuela',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 422);
            }
            $user = User::where('email', $request->email)
                ->where('baja', "<>", "*")
                ->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                $response = ObjectResponse::CatchResponse("Credenciales incorrectas");
                return response()->json($response, 404);
            }
            $access = Acceso_Usuario::where('id_usuario', '=', $user->id)->get();
            $user->permissions = $access;
            session(['escuela' => $request->xEscuela]);
            $token = $user->createToken($request->email, ['user'])->plainTextToken;
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | usuario logueado');
            data_set($response, 'token', $token);
            data_set($response, 'data', $user);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            dd($ex->getMessage());
            $response = ObjectResponse::CatchResponse($ex);
            return response()->json($response, $response['status_code']);
            //throw $th;
        }
    }
    public function recuperaContra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ], [
            'email.required' => 'El campo "Correo" es obligatorio',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        try {
            $user = User::where('email', $request->email)
                ->where('baja', "<>", "*")
                ->first();

            if ($user !== null) {
                $fechaHoraSeg = now()->format('Ymd_His');
                $passwordgenerate = Str::random(10);
                $email = $request->email;
                $DataI = [
                    "title" => "Bienvenido/a {$user->nombre}",
                    "title2" => "Generación de Contraseña",
                    "body" => "Estimado/a {$user->nombre}, su contraseña para el inicio de sesion es la siguiente: {$passwordgenerate}, puedes cambiar esta contraseña una vez entres en el sistema.",
                    "view" => "mail-template",
                    "email" => $email
                ];
                $user->password = Hash::make($passwordgenerate);
                $user->save();
                $mailController = new MailController();
                $resultado = $mailController->enviarNuevaContrasenaRegister($DataI);
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'peticion satisfactoria | Usuario registrado.');
                data_set($resultado, 'messageMail', 'Peticion satisfactoria | Contraseña Enviada');
                data_set($response, 'alert_text', "Enviamos una contraseña de recuperación al correo {$request->email}, por favor piensa en cambiar la contraseña.");
                return response()->json($response, $response['status_code']);
            } else {
                $response = ObjectResponse::CatchResponse("No se encuentra el usuario.");
                return response()->json($response, 404);
            }
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function enviarCodigoVerificacion(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'El campo "Correo" es obligatorio',
            'email.email' => 'Debe ser un correo válido',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
    
        try {
            $user = User::where('email', $request->email)
                ->where('baja', "<>", "*")
                ->first();
    
            if ($user !== null) {
                $codigoVerificacion = rand(100000, 999999);
                $expiracion = now()->addMinutes(5);
    
                Cache::put('codigo_verificacion_' . $request->email, $codigoVerificacion, $expiracion);
                Cache::put('codigo_expiracion_' . $request->email, $expiracion, $expiracion);
    
                $email = $request->email;
                $DataI = [
                    "title" => "Código de Verificación",
                    "title2" => "Tu código de verificación",
                    "body" => "Estimado/a {$user->nombre}, tu código de verificación es: {$codigoVerificacion}. Este código es válido por 5 minutos.",
                    "view" => "mail-template",
                    "email" => $email
                ];
    
                $mailController = new MailController();
                $resultado = $mailController->enviarNuevaContrasenaRegister($DataI);
    
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Código de verificación enviado.');
                data_set($response, 'alert_text', "Se ha enviado un código de verificación al correo {$request->email}");
                return response()->json($response, $response['status_code']);
            } else {
                $response = ObjectResponse::CatchResponse("No se encuentra el usuario.");
                return response()->json($response, 404);
            }
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }

    public function verificarCodigo(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'codigo' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
    
        $codigoGuardado = Cache::get('codigo_verificacion_' . $request->email);
        $expiracion = Cache::get('codigo_expiracion_' . $request->email);
    
        if (!$codigoGuardado || now()->greaterThan($expiracion)) {
            return response()->json(['message' => 'Código expirado o no encontrado'], 400);
        }
    
        if ($codigoGuardado != $request->codigo) {
            return response()->json(['message' => 'Código incorrecto'], 400);
        }
    
        Cache::forget('codigo_verificacion_' . $request->email);
        Cache::forget('codigo_expiracion_' . $request->email);
    
        return response()->json(['message' => 'Código verificado correctamente'], 200);
    }
}
