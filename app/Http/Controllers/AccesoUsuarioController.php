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
        'id_punto_menu' => 'required|integer',
        't_a' => 'required|boolean',
        'altas' => 'required|boolean',
        'bajas' => 'required|boolean',
        'cambios' => 'required|boolean',
        'impresion' => 'required|boolean',
    ];
    protected   $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'unique' => 'El campo :attribute ya ha sido registrado',
    ];
    public function index(Request $request)
    {
        $response = ObjectResponse::DefaultResponse();
        $accesos_usuarios = Acceso_Usuario::select('*')->where('id_usuario', '=', $request->id_usuario)->first();
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
            $accesos_usuarios = Acceso_Usuario::select('acceso_usuarios.*', 'accesos_menu.descripcion')
                ->join('accesos_menu', 'accesos_menu.numero', '=', 'acceso_usuarios.id_punto_menu')
                ->where('id_usuario', '=', $request->id_usuario)
                ->orderBy('accesos_menu.menu')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $accesos_usuarios);
        } else {
            $accesos_usuarios = Acceso_Usuario::select('acceso_usuarios.*', 'accesos_menu.descripcion')
                ->join('accesos_menu', 'accesos_menu.numero', '=', 'acceso_usuarios.id_punto_menu')
                ->where('id_usuario', '=', $request->id_usuario)
                ->orderBy('accesos_menu.menu')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $accesos_usuarios);
        }
        return response()->json($response, $response['status_code']);
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
