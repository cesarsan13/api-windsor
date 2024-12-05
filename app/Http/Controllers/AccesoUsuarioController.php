<?php

namespace App\Http\Controllers;

use App\Models\Acceso_Usuario;
use App\Models\AccesosMenu;
use App\Models\ObjectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccesoUsuarioController extends Controller
{
    protected $rules = [
        'id_usuario' => 'required|integer',
        'id_punto_menu' => 'integer',
        't_a' => 'boolean',
        'altas' => 'boolean',
        'bajas' => 'boolean',
        'cambios' => 'boolean',
        'impresion' => 'boolean',
    ];
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    public function index(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $accesos_usuarios = Acceso_Usuario::select('*')->where('id_usuario', '=', $request->id_usuario)->first();

        $numeroAccesosUsuarios = Acceso_Usuario::where('id_usuario', $request->id_usuario)
        ->pluck('id_punto_menu')
        ->toArray();
        $accesosFaltantes = AccesosMenu::whereNotIn('numero', $numeroAccesosUsuarios)
        ->get(['numero']);
        $numerosFaltantes = $accesosFaltantes->pluck('numero')->toArray();

        if (!$accesos_usuarios) {
            $accesosMenu = AccesosMenu::select('*')->get();
            foreach ($accesosMenu as $acceso) {
                $accesoUsuario = new Acceso_Usuario();
                $accesoUsuario->id_usuario = $request->id_usuario;
                $accesoUsuario->id_punto_menu = $acceso->numero;
                $accesoUsuario->t_a = false;
                $accesoUsuario->altas = false;
                $accesoUsuario->bajas = false;
                $accesoUsuario->cambios = false;
                $accesoUsuario->impresion = false;
                $accesoUsuario->save();
            }
        } else if (count($accesosFaltantes) > 0) { 
            foreach($numerosFaltantes as $numero => $value){
                $numeroint = intval($value);
                $accesoUsuario = new Acceso_Usuario();
                $accesoUsuario->id_usuario = $request->id_usuario;
                $accesoUsuario->id_punto_menu = $numeroint;
                $accesoUsuario->t_a = false;
                $accesoUsuario->altas = false;
                $accesoUsuario->bajas = false;
                $accesoUsuario->cambios = false;
                $accesoUsuario->impresion = false;
                $accesoUsuario->save();
            }
        } 

        $accesos_usuarios = Acceso_Usuario::select('acceso_usuarios.*', 'accesos_menu.descripcion','accesos_menu.menu')
            ->join('accesos_menu', 'accesos_menu.numero', '=', 'acceso_usuarios.id_punto_menu')
            ->where('id_usuario', '=', $request->id_usuario)
            ->orderBy('accesos_menu.menu')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $accesos_usuarios);
        
        return response()->json($response, $response['status_code']);
    }

    public function actualizaTodo(Request $request)
    {
        $reglas = [
            "id_usuario" => "required|integer",
            "name" => "required|string",
        ];
        $validator = Validator::make($request->all(), $reglas, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        $response = ObjectResponse::DefaultResponse();
        try {
            $id_usuario = $request->id_usuario;
            $opcion = $request->name;
            Acceso_Usuario::where('id_usuario', $id_usuario)
                ->update([
                    "t_a" => $opcion === "si" ? 1 : 0,
                    "altas" => $opcion === "si" ? 1 : 0,
                    "bajas" => $opcion === "si" ? 1 : 0,
                    "cambios" => $opcion === "si" ? 1 : 0,
                    "impresion" => $opcion === "si" ? 1 : 0
                ]);
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria');
            data_set($response, 'alert_text', 'Acceso Usuario actualizado');
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function update(Request $request)
    {

        $response = ObjectResponse::CorrectResponse();
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::CatchResponse($validator->errors()->all());
            return response()->json($response, $response['status_code']);
        }
        Acceso_Usuario::where('id_usuario', $request->id_usuario)
            ->where('id_punto_menu', $request->id_punto_menu)
            ->update([
                "t_a" => $request->t_a,
                "altas" => $request->altas,
                "bajas" => $request->bajas,
                "cambios" => $request->cambios,
                "impresion" => $request->impresion
            ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria');
        data_set($response, 'alert_text', 'Acceso Usuario actualizado');
        return response()->json($response, $response['status_code']);
    }
}
