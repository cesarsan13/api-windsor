<?php

namespace App\Http\Controllers;

use App\Models\ObjectResponse;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Services\GlobalService;

class ProductoController extends Controller
{
    protected $validationService;
    public function __construct(GlobalService $validationService)
    {
        $this->validationService = $validationService;
    }
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
        'numero' => 'required|max:20',
        'descripcion' => 'required|string|max:255',
        'costo' => 'required|numeric',
        'frecuencia' => 'required|string|max:20',
        'por_recargo' => 'required|numeric',
        'aplicacion' => 'required|string|max:25',
        'iva' => 'required|numeric',
        'cond_1' => 'required|integer',
        'cam_precio' => 'required|boolean',
        'ref' => 'required|string|max:20',
        'baja' => 'nullable|string|max:1',
    ];
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
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        $producto = Producto::find($request->numero);
        if ($producto) {
            $response = ObjectResponse::BadResponse('El producto ya existe, por favor ungrese un numero diferente');
            data_set($response, 'errors', ['numero' => ['Producto ya existe']]);
            return response()->json($response, $response['status_code']);
        }
        $producto = new Producto();
        $producto->numero = $request->numero;
        $producto->descripcion = $request->descripcion ?? "";
        $producto->costo = $request->costo ?? "";
        $producto->frecuencia = $request->frecuencia ?? "";
        $producto->por_recargo = $request->por_recargo ?? "";
        $producto->aplicacion = $request->aplicacion ?? "";
        $producto->iva = $request->iva ?? "";
        $producto->cond_1 = $request->cond_1 ?? "";
        $producto->cam_precio = $request->cam_precio ?? "";
        $producto->ref = $request->ref ?? "";
        $producto->baja = $request->baja ?? "";
        $producto->save();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Petición satisfactoria | Producto registrado.');
        data_set($response, 'alert_text', 'Producto registrado');
        return response()->json($response, $response['status_code']);
    }

    public function updateProduct(Request $request, $numero)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $producto = Producto::find($numero);
        if (!$producto) {
            $response = ObjectResponse::BadResponse($validator->errors());
            data_set($response, 'errors', ['numero' => ['Producto no encontrado']]);
            return response()->json($response, $response['status_code']);
        }
        $producto->descripcion = $request->descripcion;
        $producto->costo = $request->costo;
        $producto->frecuencia = $request->frecuencia;
        $producto->por_recargo = $request->por_recargo;
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

    public function storeBatchProduct(Request $request)
    {
        $data = $request->all();
        $validatedDataInsert = [];
        $alert_text = "";
        $validatedDataUpdate = [];
        $this->validationService->validateAndProcessData(
            "numero",
            $data,
            $this->rules,
            $this->messages,
            $alert_text,
            Producto::class,
            $validatedDataInsert,
            $validatedDataUpdate
        );
        if (!empty($validatedDataInsert)) {
            Producto::insert($validatedDataInsert);
        }
        if (!empty($validatedDataUpdate)) {
            foreach ($validatedDataUpdate as $updateItem) {
                Producto::where('numero', $updateItem['numero'])->update($updateItem);
            }
        }
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Lista de Productos insertados correctamente.');
        data_set($response, 'alert_text', 'Producto insertados.');
        return response()->json($response, $response['status_code']);
    }
}
