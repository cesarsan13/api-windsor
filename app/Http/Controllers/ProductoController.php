<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio.',
        'max' => 'El campo :attribute no puede tener más de :max caracteres.',
        'min' => 'El campo :attribute debe tener al menos :min caracteres.',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
        'numeric' => 'El campo :attribute debe ser un número decimal.',
        'string' => 'El campo :attribute debe ser una cadena.',
        'integer' => 'El campo :attribute debe ser un número.',
        'boolean' => 'El campo :attribute debe ser un valor booleano.',
    ];
    protected $rules = [
        'descripcion' => 'required|string',
        'costo' => 'required|numeric',
        'frecuencia' => 'required|string',
        'pro_recargo' => 'required|numeric',
        'aplicacion' => 'required|string',
        'iva' => 'required|numeric',
        'cond_1' => 'required|integer',
        'cam_precio' => 'required|boolean',
        'ref' => 'required|string',
    ];

    public function productFilter($type, $value)
    {
        switch ($type) {
            case 'id':
                $productos = DB::table('productos')->where('id', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('id', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;

            case 'descripcion':
                $productos = DB::table('productos')->where('descripcion', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('descripcion', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'costo':
                $productos = DB::table('productos')->where('costo', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('costo', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'frecuencia':
                $productos = DB::table('productos')->where('frecuencia', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('frecuencia', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'pro_recargo':
                $productos = DB::table('productos')->where('pro_recargo', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('pro_recargo', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'aplicacion':
                $productos = DB::table('productos')->where('aplicacion', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('aplicacion', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'iva':
                $productos = DB::table('productos')->where('iva', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('iva', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'cond_1':
                $productos = DB::table('productos')->where('cond_1', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('cond_1', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'cam_precio':
                $productos = DB::table('productos')->where('cam_precio', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('cam_precio', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'ref':
                $productos = DB::table('productos')->where('ref', 'like', "%{$value}%")->where('baja', '<>', '*')->orderBy('ref', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
            case 'nothing':
                $productos = DB::table('productos')->where('baja', '<>', '*')->orderBy('descripcion', 'ASC')->get();
                $response = ObjectResponse::CorrectResponse();
                data_set($response, 'message', 'Peticion satisfactoria');
                data_set($response, 'data', $productos);
                return response()->json($response, $response['status_code']);
                break;
        }
    }
    public function showProduct()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $productos = DB::table('productos')->where('baja', '<>', '*')->orderBy('descripcion', 'ASC')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $productos);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function lastProduct()
    {
        $maxId = DB::table('productos')->max('id');
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $maxId);
        return response()->json($response, $response['status_code']);
    }
    public function bajaProduct()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $productos = DB::table('productos')->where('baja', '=', '*')->orderBy('descripcion', 'ASC')->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $productos);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function storeProduct(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validacion');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $producto = Producto::find($request->id);
        if ($producto) {
            $response = ObjectResponse::BadResponse('El producto ya existe');
            data_set($response, 'errors', ['id' => ['Producto ya existe']]);
            return response()->json($response, $response['status_code']);
        }
        $producto = new Producto();
        $producto->id = $request->id;
        $producto->descripcion = $request->descripcion;
        $producto->costo = $request->costo;
        $producto->frecuencia = $request->frecuencia;
        $producto->pro_recargo = $request->pro_recargo;
        $producto->aplicacion = $request->aplicacion;
        $producto->iva = $request->iva;
        $producto->cond_1 = $request->cond_1;
        $producto->cam_precio = $request->cam_precio;
        $producto->ref = $request->ref;
        $producto->baja = $request->baja;
        $producto->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria | Producto registrado.');
        data_set($response, 'alert_text', 'Producto registrado');
        return response()->json($response, $response['status_code']);
    }

    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse($validator->errors());
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $producto = Producto::find($id);
        if (!$producto) {
            $response = ObjectResponse::BadResponse($validator->errors());
            data_set($response, 'errors', ['id' => ['Producto no encontrado']]);
            return response()->json($response, $response['status_code']);
        }
        $producto->descripcion = $request->descripcion;
        $producto->costo = $request->costo;
        $producto->frecuencia = $request->frecuencia;
        $producto->pro_recargo = $request->pro_recargo;
        $producto->aplicacion = $request->aplicacion;
        $producto->iva = $request->iva;
        $producto->cond_1 = $request->cond_1;
        $producto->cam_precio = $request->cam_precio;
        $producto->ref = $request->ref;
        $producto->baja = $request->baja;
        $producto->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'peticion satisfactoria | Producto actualizado.');
        data_set($response, 'alert_text', 'Producto actualizado.');
        return response()->json($response, $response['status_code']);
    }
}
