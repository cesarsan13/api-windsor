<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; 
//use App\Events\UsuarioInsertado;

class UsuarioController extends Controller
{
    public function GetBajaUsuarios(){
        $usuarios = DB::table('users')
        ->select('id', 'name', 'email', 'password')
        ->where('baja', '=', "*") 
        ->get();
    
    return response()->json($usuarios, 200);
    }


    public function GetUsuarios()
    {
            $usuarios = DB::table('users')
            ->select('id', 'nombre', 'name', 'email', 'password')
            ->where('baja', '!=', "*") 
            ->get();                
            return response()->json($usuarios, 200);        
    }


    public function UpdateUsuarios(Request $request, $id){       
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string', 
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
            console.log(json);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json("Usuario no encontrado", 404);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);       
        $user->baja= $request = $request->baja=" " ;
        $user->save();
        return response()->json($user, 201);

    }

    
    public function PostUsuarios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'nombre' => 'required|string', 
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
            console.log(json);
        }

        $user = new User;
        $user->id = $request->id;
        $user->nombre = $request->nombre;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        //$user->rol = 1;
        $user->baja = "";
        $user->save();

        event(new UsuarioInsertado($user));

        return response()->json(['message' => 'Usuario creado correctamente', 'usuario' => $user], 201);
    }


    public function GetXUsuario($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        $usuario = DB::table('users')
            ->select( 'name', 'email', 'password')
            ->where('id', $id)
            //->where('baja', '!=', "*") 
            ->get();
    
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        return response()->json($usuario, 200);
    }

    
    public function BajaUsuario(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $affected = DB::table('users')
        ->where('id', $id)
        ->update(['baja' => "*"]);
    return response()->json(['message' => 'Estado de baja actualizado correctamente'], 200);
    }
}