<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ObjectResponse;
use App\Models\Alumno;
use App\Models\DetallePedido;
use App\Models\Cobranza_Diaria;
use App\Models\Encab_Pedido;
use App\Models\DocsCobranza;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\GlobalService;

class AlumnoController extends Controller
{
    protected $validationService;
    public function __construct(GlobalService $validationService)
    {
        $this->validationService = $validationService;
    }
    protected $messages = [
        'required' => 'El campo :attribute es obligatorio',
        'max' => 'El campo :attribute no puede tener más de :max caracteres',
        'min' => 'El campo :attribute debe tener al menos :min caracteres',
        'unique' => 'El  :attribute ya ha sido registrado anteriormente',
        'numeric' => 'El campo :attribute debe ser un número decimal',
        'string' => 'El campo :attribute debe ser una cadena',
        'integer' => 'El campo :attribute debe ser un número',
        'boolean' => 'El campo :attribute debe ser un valor booleano',
    ];
    protected $rules2 = [
        'numero_ant' => 'required|integer',
        'numero_nuev' => 'required|integer',
    ];
    protected $rules = [
        'numero' => 'required|numeric',
        'nombre' => 'required|string|max:50',
        'a_paterno' => 'required|string|max:50',
        'a_materno' => 'required|string|max:50',
        'a_nombre' => 'nullable|string|max:50',
        'fecha_nac' => 'required',
        'fecha_inscripcion' => 'required',
        'fecha_baja' => 'nullable',
        'sexo' => 'required|string|max:15',
        'telefono1' => 'required|string|max:15',
        'telefono2' => 'nullable|string|max:15',
        'celular' => 'required|string|max:15',
        'codigo_barras' => 'nullable|string|max:50',
        'direccion' => 'required|string',
        'colonia' => 'required|string|max:100',
        'ciudad' => 'required|string|max:100',
        'estado' => 'required|string|max:100',
        'cp' => 'required|string|max:10',
        // 'email' => 'required|string|email|max:255',
        'email' => 'required|string|max:255',
        'ruta_foto' => 'nullable',
        'dia_1' => 'nullable|string|max:20',
        'dia_2' => 'nullable|string|max:20',
        'dia_3' => 'nullable|string|max:20',
        'dia_4' => 'nullable|string|max:20',
        'hora_1' => 'nullable|integer|max:30',
        'hora_2' => 'nullable|integer|max:30',
        'hora_3' => 'nullable|integer|max:30',
        'hora_4' => 'nullable|integer|max:30',
        'cancha_1' => 'nullable',
        'cancha_2' => 'nullable',
        'cancha_3' => 'nullable',
        'cancha_4' => 'nullable',
        'horario_1' => 'nullable',
        'horario_2' => 'nullable',
        'horario_3' => 'nullable',
        'horario_4' => 'nullable',
        'horario_5' => 'nullable',
        'horario_6' => 'nullable',
        'horario_7' => 'nullable',
        'horario_8' => 'nullable',
        'horario_9' => 'nullable',
        'horario_10' => 'nullable',
        'horario_11' => 'nullable',
        'horario_12' => 'nullable',
        'horario_13' => 'nullable',
        'horario_14' => 'nullable',
        'horario_15' => 'nullable',
        'horario_16' => 'nullable',
        'horario_17' => 'nullable',
        'horario_18' => 'nullable',
        'horario_19' => 'nullable',
        'horario_20' => 'nullable',
        'cond_1' => 'nullable',
        'cond_2' => 'nullable',
        'cond_3' => 'nullable',
        'nom_pediatra' => 'nullable|string|max:50',
        'tel_p_1' => 'nullable|string|max:15',
        'tel_p_2' => 'nullable|string|max:15',
        'cel_p_1' => 'nullable|string|max:15',
        'tipo_sangre' => 'nullable|string|max:20',
        'alergia' => 'nullable|string|max:50',
        'aseguradora' => 'nullable|string|max:100',
        'poliza' => 'nullable|string|max:30',
        'tel_ase_1' => 'nullable|string|max:15',
        'tel_ase_2' => 'nullable|string|max:15',
        'razon_social' => 'nullable|string|max:30',
        'raz_direccion' => 'nullable|string',
        'raz_colonia' => 'nullable|string|max:100',
        'raz_ciudad' => 'nullable|string|max:100',
        'raz_estado' => 'nullable|string|max:100',
        'raz_cp' => 'nullable|string|max:10',
        'nom_padre' => 'nullable|string|max:50',
        'tel_pad_1' => 'nullable|string|max:15',
        'tel_pad_2' => 'nullable|string|max:15',
        'cel_pad' => 'nullable|string|max:15',
        'nom_madre' => 'nullable|string|max:50',
        'tel_mad_1' => 'nullable|string|max:15',
        'tel_mad_2' => 'nullable|string|max:15',
        'cel_mad' => 'nullable|string|max:15',
        'nom_avi' => 'nullable|string|max:50',
        'tel_avi_1' => 'nullable|string|max:15',
        'tel_avi_2' => 'nullable|string|max:15',
        'cel_avi' => 'nullable|string|max:15',
        'ciclo_escolar' => 'nullable|string|max:50',
        'descuento' => 'nullable|numeric',
        'rfc_factura' => 'nullable|string|max:50',
        'estatus' => 'required|string|max:20',
        'escuela' => 'nullable|string|max:50',
        'grupo' => 'nullable|string|max:15',
        'baja' => 'nullable|string|max:1',
    ];
    public function showImageStudents($imagen)
    {
        if (file_exists(public_path('images/alumnos/' . $imagen))) {
            return response()->file(public_path('images/alumnos/' . $imagen));
        }
    }
    public function getReportAltaBajaAlumno(Request $request)
    {
        $baja = $request->input('baja');
        $tipoOrden = $request->input('tipoOrden');
        $fecha_ini = $request->input('fecha_ini');
        $fecha_fin = $request->input('fecha_fin');

        $query = DB::table('alumnos as al')
            ->leftJoin('horarios as hr1', 'al.horario_1', '=', 'hr1.numero')
            ->select(
                'al.numero',
                DB::raw("CONCAT(al.a_nombre, ' ', al.a_paterno, ' ', al.a_materno) as nombre_completo"),
                'al.a_paterno',
                'al.a_materno',
                'al.a_nombre',
                'al.fecha_nac',
                'al.fecha_inscripcion',
                'al.fecha_baja',
                'al.sexo',
                'al.telefono1',
                'al.telefono2',
                'al.celular',
                'al.codigo_barras',
                'al.direccion',
                'al.colonia',
                'al.ciudad',
                'al.estado',
                'al.cp',
                'al.email',
                'al.ruta_foto',
                'al.dia_1',
                'al.dia_2',
                'al.dia_3',
                'al.dia_4',
                'al.hora_1',
                'al.hora_2',
                'al.hora_3',
                'al.hora_4',
                'al.cancha_1',
                'al.cancha_2',
                'al.cancha_3',
                'al.cancha_4',
                'al.horario_1',
                'hr1.horario as horario_1_nombre',
                'al.horario_2',
                'al.horario_3',
                'al.horario_4',
                'al.horario_5',
                'al.horario_6',
                'al.horario_7',
                'al.horario_8',
                'al.horario_9',
                'al.horario_10',
                'al.horario_11',
                'al.horario_12',
                'al.horario_13',
                'al.horario_14',
                'al.horario_15',
                'al.horario_16',
                'al.horario_17',
                'al.horario_18',
                'al.horario_19',
                'al.horario_20',
                'al.cond_1',
                'al.cond_2',
                'al.cond_3',
                'al.nom_pediatra',
                'al.tel_p_1',
                'al.tel_p_2',
                'al.cel_p_1',
                'al.tipo_sangre',
                'al.alergia',
                'al.aseguradora',
                'al.poliza',
                'al.tel_ase_1',
                'al.tel_ase_2',
                'al.razon_social',
                'al.raz_direccion',
                'al.raz_colonia',
                'al.raz_ciudad',
                'al.raz_estado',
                'al.raz_cp',
                'al.nom_padre',
                'al.tel_pad_1',
                'al.tel_pad_2',
                'al.cel_pad',
                'al.nom_madre',
                'al.tel_mad_1',
                'al.tel_mad_2',
                'al.cel_mad',
                'al.nom_avi',
                'al.tel_avi_1',
                'al.tel_avi_2',
                'al.cel_avi',
                'al.ciclo_escolar',
                'al.descuento',
                'al.rfc_factura',
                'al.estatus',
                'al.escuela',
                'al.grupo',
                'al.baja'
            );

        if ($baja === 'alta') {
            if ($fecha_ini > 0 || $fecha_fin > 0) {
                if ($fecha_fin == 0) {
                    $query->where('al.fecha_inscripcion', '=', $fecha_ini);
                } else {
                    $query->whereBetween('al.fecha_inscripcion', [$fecha_ini, $fecha_fin]);
                }
            }
        } else {
            if ($fecha_ini > 0 || $fecha_fin > 0) {
                if ($fecha_fin == 0) {
                    $query->where('al.fecha_baja', '=', $fecha_ini);
                } else {
                    $query->whereBetween('al.fecha_baja', [$fecha_ini, $fecha_fin]);
                }
            }
        }

        if ($tipoOrden == 'nombre') {
            $query->orderBy('al.nombre', 'ASC');
        } else if ($tipoOrden == 'Numero') {
            $query->orderBy('al.numero', 'ASC');
        } else if ($tipoOrden == 'Fecha_nac') {
            $query->orderBy('al.fecha_nac', 'ASC');
        }

        $resultados = $query->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $resultados);
        data_set($response, 'envio', $baja . $tipoOrden);
        return response()->json($response, $response['status_code']);
    }
    public function getReportAlumn(Request $request) {
        $baja = $request->input('baja');
        $tipoOrden = $request->input('tipoOrden');
        $alumnos1 = $request->input('alumnos1');
        $alumnos2 = $request->input('alumnos2');
    
        $query = DB::table('alumnos as al')
            ->leftJoin('horarios as hr1', 'al.horario_1', '=', 'hr1.numero')
            ->select(
                'al.numero',
                DB::raw("CONCAT(al.a_nombre, ' ', al.a_paterno, ' ', al.a_materno) as nombre_completo"),
                'al.a_paterno',
                'al.a_materno',
                'al.a_nombre',
                'al.fecha_nac',
                'al.fecha_inscripcion',
                'al.fecha_baja',
                'al.sexo',
                'al.telefono1',
                'al.telefono2',
                'al.celular',
                'al.codigo_barras',
                'al.direccion',
                'al.colonia',
                'al.ciudad',
                'al.estado',
                'al.cp',
                'al.email',
                'al.ruta_foto',
                'al.dia_1',
                'al.dia_2',
                'al.dia_3',
                'al.dia_4',
                'al.hora_1',
                'al.hora_2',
                'al.hora_3',
                'al.hora_4',
                'al.cancha_1',
                'al.cancha_2',
                'al.cancha_3',
                'al.cancha_4',
                'al.horario_1',
                'hr1.horario as horario_1_nombre',
                'al.horario_2',
                'al.horario_3',
                'al.horario_4',
                'al.horario_5',
                'al.horario_6',
                'al.horario_7',
                'al.horario_8',
                'al.horario_9',
                'al.horario_10',
                'al.horario_11',
                'al.horario_12',
                'al.horario_13',
                'al.horario_14',
                'al.horario_15',
                'al.horario_16',
                'al.horario_17',
                'al.horario_18',
                'al.horario_19',
                'al.horario_20',
                'al.cond_1',
                'al.cond_2',
                'al.cond_3',
                'al.nom_pediatra',
                'al.tel_p_1',
                'al.tel_p_2',
                'al.cel_p_1',
                'al.tipo_sangre',
                'al.alergia',
                'al.aseguradora',
                'al.poliza',
                'al.tel_ase_1',
                'al.tel_ase_2',
                'al.razon_social',
                'al.raz_direccion',
                'al.raz_colonia',
                'al.raz_ciudad',
                'al.raz_estado',
                'al.raz_cp',
                'al.nom_padre',
                'al.tel_pad_1',
                'al.tel_pad_2',
                'al.cel_pad',
                'al.nom_madre',
                'al.tel_mad_1',
                'al.tel_mad_2',
                'al.cel_mad',
                'al.nom_avi',
                'al.tel_avi_1',
                'al.tel_avi_2',
                'al.cel_avi',
                'al.ciclo_escolar',
                'al.descuento',
                'al.rfc_factura',
                'al.estatus',
                'al.escuela',
                'al.grupo',
                'al.baja'
            );
    
        if ($baja === true) {
            $query->where('al.nombre', '<>', ' ');
        } else {
            $query->where('al.baja', '<>', '*');
        }
    
        if ($alumnos1 > 0 || $alumnos2 > 0) {
            if ($alumnos2 == 0) {
                $query->where('al.numero', '=', $alumnos1);
            } else {
                $query->whereBetween('al.numero', [$alumnos1, $alumnos2]);
            }
        }
    
        if ($tipoOrden !== 'todos') {
            $query->orderBy($tipoOrden, 'ASC');
        }
    
        $resultados = $query->get();
    
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        data_set($response, 'data', $resultados);
        return response()->json($response, $response['status_code']);
    }
    
    public function showAlumn()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $alumnos = DB::table('alumnos as al')
                ->leftJoin('horarios as hr1', 'al.horario_1', '=', 'hr1.numero')
                ->leftJoin('tipo_cobro as tp1', 'al.cond_1', '=', 'tp1.numero')
                ->leftJoin('tipo_cobro as tp2', 'al.cond_2', '=', 'tp2.numero')
                ->select(
                    'al.numero',
                    DB::raw("CONCAT(al.a_nombre, ' ', al.a_paterno, ' ', al.a_materno) as nombre_completo"),
                    'al.nombre',
                    'al.a_paterno',
                    'al.a_materno',
                    'al.a_nombre',
                    'al.fecha_nac',
                    'al.fecha_inscripcion',
                    'al.fecha_baja',
                    'al.sexo',
                    'al.telefono1',
                    'al.telefono2',
                    'al.celular',
                    'al.codigo_barras',
                    'al.direccion',
                    'al.colonia',
                    'al.ciudad',
                    'al.estado',
                    'al.cp',
                    'al.email',
                    'al.ruta_foto',
                    'al.dia_1',
                    'al.dia_2',
                    'al.dia_3',
                    'al.dia_4',
                    'al.hora_1',
                    'al.hora_2',
                    'al.hora_3',
                    'al.hora_4',
                    'al.cancha_1',
                    'al.cancha_2',
                    'al.cancha_3',
                    'al.cancha_4',
                    'al.horario_1',
                    'hr1.horario as horario_1_nombre',
                    'al.horario_2',
                    'al.horario_3',
                    'al.horario_4',
                    'al.horario_5',
                    'al.horario_6',
                    'al.horario_7',
                    'al.horario_8',
                    'al.horario_9',
                    'al.horario_10',
                    'al.horario_11',
                    'al.horario_12',
                    'al.horario_13',
                    'al.horario_14',
                    'al.horario_15',
                    'al.horario_16',
                    'al.horario_17',
                    'al.horario_18',
                    'al.horario_19',
                    'al.horario_20',
                    'al.cond_1',
                    'tp1.descripcion as cond_1_nombre',
                    'al.cond_2',
                    'tp2.descripcion as cond_2_nombre',
                    'al.cond_3',
                    'al.nom_pediatra',
                    'al.tel_p_1',
                    'al.tel_p_2',
                    'al.cel_p_1',
                    'al.tipo_sangre',
                    'al.alergia',
                    'al.aseguradora',
                    'al.poliza',
                    'al.tel_ase_1',
                    'al.tel_ase_2',
                    'al.razon_social',
                    'al.raz_direccion',
                    'al.raz_colonia',
                    'al.raz_ciudad',
                    'al.raz_estado',
                    'al.raz_cp',
                    'al.nom_padre',
                    'al.tel_pad_1',
                    'al.tel_pad_2',
                    'al.cel_pad',
                    'al.nom_madre',
                    'al.tel_mad_1',
                    'al.tel_mad_2',
                    'al.cel_mad',
                    'al.nom_avi',
                    'al.tel_avi_1',
                    'al.tel_avi_2',
                    'al.cel_avi',
                    'al.ciclo_escolar',
                    'al.descuento',
                    'al.rfc_factura',
                    'al.estatus',
                    'al.escuela',
                    'al.grupo',
                    'al.baja'
                )
                ->where('al.baja', '<>', '*')
                ->orderBy('al.numero', 'ASC')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $alumnos);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function lastAlumn()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $siguiente = DB::table('alumnos')->max('numero');
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'peticion satisfactoria | Siguiente Alumno');
            data_set($response, 'alert_text', 'Siguiente Alumno');
            data_set($response, 'data', $siguiente);
       } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
        }
        return response()->json($response, $response["status_code"]);
    }
    public function dataAlumSex()
    {

        try {
            $tsql = "select categoria,count(*) as Cantidad from(";
            $tsql .= "Select ";
            $tsql .= " Case ";
            $tsql .= " When sexo='H' Then 'Hombres' ";
            $tsql .= " When sexo='M' OR sexo='F' Then 'Mujeres'";
            $tsql .= " When sexo IS NULL OR  sexo = '' Then 'Vacio/Otros'";
            $tsql .= " ELSE 'Otros'";
            $tsql .= " END AS categoria";
            $tsql .= " FROM alumnos where baja<>'*')as dt";
            $tsql .= " Group By categoria";

            $resultados = DB::select($tsql);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $resultados);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function bajaAlumn()
    {
        $response = ObjectResponse::DefaultResponse();
        try {
            $alumnos = DB::table('alumnos as al')
                ->leftJoin('horarios as hr1', 'al.horario_1', '=', 'hr1.numero')
                ->select(
                    'al.numero',
                    DB::raw("CONCAT(al.nombre, ' ', al.a_paterno, ' ', al.a_materno) as nombre_completo"),
                    'al.nombre',
                    'al.a_paterno',
                    'al.a_materno',
                    'al.a_nombre',
                    'al.fecha_nac',
                    'al.fecha_inscripcion',
                    'al.fecha_baja',
                    'al.sexo',
                    'al.telefono1',
                    'al.telefono2',
                    'al.celular',
                    'al.codigo_barras',
                    'al.direccion',
                    'al.colonia',
                    'al.ciudad',
                    'al.estado',
                    'al.cp',
                    'al.email',
                    'al.ruta_foto',
                    'al.dia_1',
                    'al.dia_2',
                    'al.dia_3',
                    'al.dia_4',
                    'al.hora_1',
                    'al.hora_2',
                    'al.hora_3',
                    'al.hora_4',
                    'al.cancha_1',
                    'al.cancha_2',
                    'al.cancha_3',
                    'al.cancha_4',
                    'al.horario_1',
                    'hr1.horario as horario_1_nombre',
                    'al.horario_2',
                    'al.horario_3',
                    'al.horario_4',
                    'al.horario_5',
                    'al.horario_6',
                    'al.horario_7',
                    'al.horario_8',
                    'al.horario_9',
                    'al.horario_10',
                    'al.horario_11',
                    'al.horario_12',
                    'al.horario_13',
                    'al.horario_14',
                    'al.horario_15',
                    'al.horario_16',
                    'al.horario_17',
                    'al.horario_18',
                    'al.horario_19',
                    'al.horario_20',
                    'al.cond_1',
                    'al.cond_2',
                    'al.cond_3',
                    'al.nom_pediatra',
                    'al.tel_p_1',
                    'al.tel_p_2',
                    'al.cel_p_1',
                    'al.tipo_sangre',
                    'al.alergia',
                    'al.aseguradora',
                    'al.poliza',
                    'al.tel_ase_1',
                    'al.tel_ase_2',
                    'al.razon_social',
                    'al.raz_direccion',
                    'al.raz_colonia',
                    'al.raz_ciudad',
                    'al.raz_estado',
                    'al.raz_cp',
                    'al.nom_padre',
                    'al.tel_pad_1',
                    'al.tel_pad_2',
                    'al.cel_pad',
                    'al.nom_madre',
                    'al.tel_mad_1',
                    'al.tel_mad_2',
                    'al.cel_mad',
                    'al.nom_avi',
                    'al.tel_avi_1',
                    'al.tel_avi_2',
                    'al.cel_avi',
                    'al.ciclo_escolar',
                    'al.descuento',
                    'al.rfc_factura',
                    'al.estatus',
                    'al.escuela',
                    'al.grupo',
                    'al.baja'
                )
                ->where('al.baja', '=', '*')
                ->orderBy('al.numero', 'ASC')
                ->get();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Peticion satisfactoria');
            data_set($response, 'data', $alumnos);
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            return response()->json($response, $response['status_code']);
        }
    }
    public function storeAlumn(Request $request)
    {
        $response = $this->lastAlumn();
        $nuevo_id = intval($response->getData()->data) + 1;
        $request->merge(['numero' => $nuevo_id]);
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }

        $alumno = Alumno::find($request->numero);
        if ($alumno) {
            $response = ObjectResponse::BadResponse('El alumno ya existe');
            data_set($response, 'errors', ['numero' => ['Alumno ya existe']]);
            return response()->json($response, $response['status_code']);
        }

        Log::info('Datos de la petición:', ['data' => $request->all()]);
        $alumno = new Alumno();
        $alumno->numero = $request->numero ?? 0;
        $alumno->nombre = $request->nombre ?? '';
        $alumno->a_paterno = $request->a_paterno ?? '';
        $alumno->a_materno = $request->a_materno ?? '';
        $alumno->a_nombre = $request->a_nombre ?? '';
        $alumno->fecha_nac = $request->fecha_nac ?? '';
        $alumno->fecha_inscripcion = $request->fecha_inscripcion ?? '';
        $alumno->fecha_baja = $request->fecha_baja ?? '';
        $alumno->sexo = $request->sexo ?? '';
        $alumno->telefono1 = $request->telefono1 ?? '';
        $alumno->telefono2 = $request->telefono2 ?? '';
        $alumno->celular = $request->celular ?? '';
        $alumno->codigo_barras = $request->codigo_barras ?? '';
        $alumno->direccion = $request->direccion ?? '';
        $alumno->colonia = $request->colonia ?? '';
        $alumno->ciudad = $request->ciudad ?? '';
        $alumno->estado = $request->estado ?? '';
        $alumno->cp = $request->cp ?? '';
        $alumno->email = $request->email ?? '';
        $alumno->dia_1 = $request->dia_1 ?? '';
        $alumno->dia_2 = $request->dia_2 ?? '';
        $alumno->dia_3 = $request->dia_3 ?? '';
        $alumno->dia_4 = $request->dia_4 ?? '';
        $alumno->hora_1 = $request->hora_1 ?? '';
        $alumno->hora_2 = $request->hora_2 ?? '';
        $alumno->hora_3 = $request->hora_3 ?? '';
        $alumno->hora_4 = $request->hora_4 ?? '';
        $alumno->cancha_1 = $request->cancha_1 ?? 0;
        $alumno->cancha_2 = $request->cancha_2 ?? 0;
        $alumno->cancha_3 = $request->cancha_3 ?? 0;
        $alumno->cancha_4 = $request->cancha_4 ?? 0;
        $alumno->horario_1 = $request->horario_1 ?? 0;
        $alumno->horario_2 = $request->horario_2 ?? 0;
        $alumno->horario_3 = $request->horario_3 ?? 0;
        $alumno->horario_4 = $request->horario_4 ?? 0;
        $alumno->horario_5 = $request->horario_5 ?? 0;
        $alumno->horario_6 = $request->horario_6 ?? 0;
        $alumno->horario_7 = $request->horario_7 ?? 0;
        $alumno->horario_8 = $request->horario_8 ?? 0;
        $alumno->horario_9 = $request->horario_9 ?? 0;
        $alumno->horario_10 = $request->horario_10 ?? 0;
        $alumno->horario_11 = $request->horario_11 ?? 0;
        $alumno->horario_12 = $request->horario_12 ?? 0;
        $alumno->horario_13 = $request->horario_13 ?? 0;
        $alumno->horario_14 = $request->horario_14 ?? 0;
        $alumno->horario_15 = $request->horario_15 ?? 0;
        $alumno->horario_16 = $request->horario_16 ?? 0;
        $alumno->horario_17 = $request->horario_17 ?? 0;
        $alumno->horario_18 = $request->horario_18 ?? 0;
        $alumno->horario_19 = $request->horario_19 ?? 0;
        $alumno->horario_20 = $request->horario_20 ?? 0;
        $alumno->cond_1 = $request->cond_1 ?? 0;
        $alumno->cond_2 = $request->cond_2 ?? 0;
        $alumno->cond_3 = $request->cond_3 ?? 0;
        $alumno->nom_pediatra = $request->nom_pediatra ?? '';
        $alumno->tel_p_1 = $request->tel_p_1 ?? '';
        $alumno->tel_p_2 = $request->tel_p_2 ?? '';
        $alumno->cel_p_1 = $request->cel_p_1 ?? '';
        $alumno->tipo_sangre = $request->tipo_sangre ?? '';
        $alumno->alergia = $request->alergia ?? '';
        $alumno->aseguradora = $request->aseguradora ?? '';
        $alumno->poliza = $request->poliza ?? '';
        $alumno->tel_ase_1 = $request->tel_ase_1 ?? '';
        $alumno->tel_ase_2 = $request->tel_ase_2 ?? '';
        $alumno->razon_social = $request->razon_social ?? '';
        $alumno->raz_direccion = $request->raz_direccion ?? '';
        $alumno->raz_colonia = $request->raz_colonia ?? '';
        $alumno->raz_ciudad = $request->raz_ciudad ?? '';
        $alumno->raz_estado = $request->raz_estado ?? '';
        $alumno->raz_cp = $request->raz_cp ?? 0;
        $alumno->nom_padre = $request->nom_padre ?? '';
        $alumno->tel_pad_1 = $request->tel_pad_1 ?? '';
        $alumno->tel_pad_2 = $request->tel_pad_2 ?? '';
        $alumno->cel_pad = $request->cel_pad ?? '';
        $alumno->nom_madre = $request->nom_madre ?? '';
        $alumno->tel_mad_1 = $request->tel_mad_1 ?? '';
        $alumno->tel_mad_2 = $request->tel_mad_2 ?? '';
        $alumno->cel_mad = $request->cel_mad ?? '';
        $alumno->nom_avi = $request->nom_avi ?? '';
        $alumno->tel_avi_1 = $request->tel_avi_1 ?? '';
        $alumno->tel_avi_2 = $request->tel_avi_2 ?? '';
        $alumno->cel_avi = $request->cel_avi ?? '';
        $alumno->ciclo_escolar = $request->ciclo_escolar ?? '';
        $alumno->descuento = $request->descuento ?? 0;
        $alumno->rfc_factura = $request->rfc_factura ?? '';
        $alumno->estatus = $request->estatus ?? '';
        $alumno->escuela = $request->escuela ?? '';
        $alumno->grupo = $request->grupo ?? '';
        $alumno->baja = $request->baja ?? '';
        if ($request->hasFile('ruta_foto')) {
            $image = $request->file('ruta_foto');
            $destinationPath = "images/alumnos";
            $imageName = $image->getClientOriginalName();
            $fullPath = $destinationPath . '/' . $imageName;
            $fullPath = $imageName;
            if (file_exists($fullPath)) {
                $alumno->ruta_foto = $fullPath;
            } else {
                $uploadSuccess = $image->move($destinationPath, $imageName);
                $alumno->ruta_foto = $fullPath;
            }
            $alumno->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Alumno registrado.');
            data_set($response, 'alert_text', 'Alumno registrado');
            data_set($response, 'data', $request->numero);
            return response()->json($response, $response['status_code']);
        } else {
            $alumno->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Alumno registrado.');
            data_set($response, 'alert_text', 'Alumno registrado');
            data_set($response, 'data', $request->numero);
            return response()->json($response, $response['status_code']);
        }
    }
    public function changeIdAlumno(Request $request)
    {
        $rules2 = $this->rules2;
        $mensajes = $this->messages;
        $validator = Validator::make($request->all(), $rules2, $mensajes);
        if ($validator->fails()) {
            Log::info($validator->errors());
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $mensajes) {
                $errors[$field] = implode(', ', $mensajes);
            }
            $mensaje = "Error de validación:\n";
            foreach ($errors as $error) {
                $mensaje .= $error . "\n";
            }
            $response = ObjectResponse::BadResponse($mensaje);
            data_set($response, 'errors', $errors);
            return response()->json($response, $response['status_code']);
        }
        $alumno3 = Alumno::find($request->numero_ant);
        if (!$alumno3) {
            $response = ObjectResponse::BadResponse('El alumno no existe, cambie a otro numero.');
            data_set($response, 'errors', ['numero' => ['Alumno no existe, cambie a otro numero.']]);
            return response()->json($response, $response['status_code']);
        }
        $alumno = Alumno::find($request->numero_nuev);
        if ($alumno) {
            $response = ObjectResponse::BadResponse('El alumno ya existe, cambie a otro numero.');
            data_set($response, 'errors', ['numero' => ['Alumno ya existe, cambie a otro numero.']]);
            return response()->json($response, $response['status_code']);
        }
        // detalle_pedido
        // cobranza_diaria
        // encab_pedido
        // documentos_cobranza
        // socio_paquete
        try {
            $alumno2 = Alumno::find($request->numero_ant);
            $alumno2->numero = $request->numero_nuev ?? '';
            $alumno2->save();

            DB::table('detalle_pedido')
                ->where('alumno', $request->numero_ant)
                ->update(['alumno' => $request->numero_nuev ?? '']);

            DB::table('cobranza_diaria')
                ->where('alumno', $request->numero_ant)
                ->update(['alumno' => $request->numero_nuev ?? '']);

            DB::table('encab_pedido')
                ->where('alumno', $request->numero_ant)
                ->update(['alumno' => $request->numero_nuev ?? '']);

            DB::table('documentos_cobranza')
                ->where('alumno', $request->numero_ant)
                ->update(['alumno' => $request->numero_nuev ?? '']);

            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Se actualizo el numero.');
            data_set($response, 'alert_text', 'Se actualizo el numero del alumno');
            return response()->json($response, $response['status_code']);
        } catch (\Exception $ex) {
            $response = ObjectResponse::CatchResponse($ex->getMessage());
            data_set($response, 'message', 'Peticion fallida | Actualizacion de Numero de Alumno');
            data_set($response, 'alert_text', $ex);
            data_set($response, 'alert_icon', 'error');
            data_set($response, 'data', $ex);
            return response()->json($response, $response['status_code']);
        }
    }
    public function cambiarCicloAlumnos(Request $request)
    {
        $rules = [
            'ciclo_escolar' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = ObjectResponse::BadResponse('Error de validacion');
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        DB::table('alumnos')->update([
            'ciclo_escolar' => $request->ciclo_escolar
        ]);
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'alert_text', 'Se actualizó el ciclo escolar de todos los alumnos');
        return response()->json($response, $response['status_code']);
    }
    public function getCicloAlumnos()
    {
        $ciclo = Alumno::select('ciclo_escolar')->first();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $ciclo ?? 0);
        data_set($response, 'message', 'Petición satisfactoria | Cumpleañeros del mes.');
        return response()->json($response, $response['status_code']);
    }
    public function cumpleanerosDelMes()
    {
        $cumpleaneros = Alumno::select('nombre', 'fecha_nac')
            ->whereMonth('fecha_nac', date('m'))
            ->orderByRaw('DAY(fecha_nac) asc')
            ->get();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'data', $cumpleaneros);
        data_set($response, 'message', 'Petición satisfactoria | Cumpleañeros del mes.');
        return response()->json($response, $response['status_code']);
    }
    public function updateAlumn(Request $request, $numero)
    {
        $rules = $this->rules;
        $rules['numero'] = 'required|integer|unique:alumnos,numero,' . $numero . ',numero';
        $validator = Validator::make($request->all(), $rules, $this->messages);
        if ($validator->fails()) {
            // dd(vars: $validator);
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'errors', $validator->errors());
            return response()->json($response, $response['status_code']);
        }
        $alumno = Alumno::find($numero);
        if (!$alumno) {
            $response = ObjectResponse::BadResponse('El alumno no existe');
            data_set($response, 'errors', ['numero' => ['Alumno no existe']]);
            return response()->json($response, $response['status_code']);
        }

        Log::info('Datos de la petición:', ['data' => $request->all()]);
        $alumno->nombre = $request->nombre ?? '';
        $alumno->a_paterno = $request->a_paterno ?? '';
        $alumno->a_materno = $request->a_materno ?? '';
        $alumno->a_nombre = $request->a_nombre ?? '';
        $alumno->fecha_nac = $request->fecha_nac ?? '';
        $alumno->fecha_inscripcion = $request->fecha_inscripcion ?? '';
        $alumno->fecha_baja = $request->fecha_baja ?? '';
        $alumno->sexo = $request->sexo ?? '';
        $alumno->telefono1 = $request->telefono1 ?? '';
        $alumno->telefono2 = $request->telefono2 ?? '';
        $alumno->celular = $request->celular ?? '';
        $alumno->codigo_barras = $request->codigo_barras ?? '';
        $alumno->direccion = $request->direccion ?? '';
        $alumno->colonia = $request->colonia ?? '';
        $alumno->ciudad = $request->ciudad ?? '';
        $alumno->estado = $request->estado ?? '';
        $alumno->cp = $request->cp ?? '';
        $alumno->email = $request->email ?? '';
        $alumno->dia_1 = $request->dia_1 ?? '';
        $alumno->dia_2 = $request->dia_2 ?? '';
        $alumno->dia_3 = $request->dia_3 ?? '';
        $alumno->dia_4 = $request->dia_4 ?? '';
        $alumno->hora_1 = $request->hora_1 ?? '';
        $alumno->hora_2 = $request->hora_2 ?? '';
        $alumno->hora_3 = $request->hora_3 ?? '';
        $alumno->hora_4 = $request->hora_4 ?? '';
        $alumno->cancha_1 = $request->cancha_1 ?? 0;
        $alumno->cancha_2 = $request->cancha_2 ?? 0;
        $alumno->cancha_3 = $request->cancha_3 ?? 0;
        $alumno->cancha_4 = $request->cancha_4 ?? 0;
        $alumno->horario_1 = $request->horario_1 ?? 0;
        $alumno->horario_2 = $request->horario_2 ?? 0;
        $alumno->horario_3 = $request->horario_3 ?? 0;
        $alumno->horario_4 = $request->horario_4 ?? 0;
        $alumno->horario_5 = $request->horario_5 ?? 0;
        $alumno->horario_6 = $request->horario_6 ?? 0;
        $alumno->horario_7 = $request->horario_7 ?? 0;
        $alumno->horario_8 = $request->horario_8 ?? 0;
        $alumno->horario_9 = $request->horario_9 ?? 0;
        $alumno->horario_10 = $request->horario_10 ?? 0;
        $alumno->horario_11 = $request->horario_11 ?? 0;
        $alumno->horario_12 = $request->horario_12 ?? 0;
        $alumno->horario_13 = $request->horario_13 ?? 0;
        $alumno->horario_14 = $request->horario_14 ?? 0;
        $alumno->horario_15 = $request->horario_15 ?? 0;
        $alumno->horario_16 = $request->horario_16 ?? 0;
        $alumno->horario_17 = $request->horario_17 ?? 0;
        $alumno->horario_18 = $request->horario_18 ?? 0;
        $alumno->horario_19 = $request->horario_19 ?? 0;
        $alumno->horario_20 = $request->horario_20 ?? 0;
        $alumno->cond_1 = $request->cond_1 ?? 0;
        $alumno->cond_2 = $request->cond_2 ?? 0;
        $alumno->cond_3 = $request->cond_3 ?? 0;
        $alumno->nom_pediatra = $request->nom_pediatra ?? '';
        $alumno->tel_p_1 = $request->tel_p_1 ?? '';
        $alumno->tel_p_2 = $request->tel_p_2 ?? '';
        $alumno->cel_p_1 = $request->cel_p_1 ?? '';
        $alumno->tipo_sangre = $request->tipo_sangre ?? '';
        $alumno->alergia = $request->alergia ?? '';
        $alumno->aseguradora = $request->aseguradora ?? '';
        $alumno->poliza = $request->poliza ?? '';
        $alumno->tel_ase_1 = $request->tel_ase_1 ?? '';
        $alumno->tel_ase_2 = $request->tel_ase_2 ?? '';
        $alumno->razon_social = $request->razon_social ?? '';
        $alumno->raz_direccion = $request->raz_direccion ?? '';
        $alumno->raz_colonia = $request->raz_colonia ?? '';
        $alumno->raz_ciudad = $request->raz_ciudad ?? '';
        $alumno->raz_estado = $request->raz_estado ?? '';
        $alumno->raz_cp = $request->raz_cp ?? 0;
        $alumno->nom_padre = $request->nom_padre ?? '';
        $alumno->tel_pad_1 = $request->tel_pad_1 ?? '';
        $alumno->tel_pad_2 = $request->tel_pad_2 ?? '';
        $alumno->cel_pad = $request->cel_pad ?? '';
        $alumno->nom_madre = $request->nom_madre ?? '';
        $alumno->tel_mad_1 = $request->tel_mad_1 ?? '';
        $alumno->tel_mad_2 = $request->tel_mad_2 ?? '';
        $alumno->cel_mad = $request->cel_mad ?? '';
        $alumno->nom_avi = $request->nom_avi ?? '';
        $alumno->tel_avi_1 = $request->tel_avi_1 ?? '';
        $alumno->tel_avi_2 = $request->tel_avi_2 ?? '';
        $alumno->cel_avi = $request->cel_avi ?? '';
        $alumno->ciclo_escolar = $request->ciclo_escolar ?? '';
        $alumno->descuento = $request->descuento ?? 0;
        $alumno->rfc_factura = $request->rfc_factura ?? '';
        $alumno->estatus = $request->estatus ?? '';
        $alumno->escuela = $request->escuela ?? '';
        $alumno->grupo = $request->grupo ?? '';
        $alumno->baja = $request->baja ?? '';

        if ($request->hasFile('ruta_foto')) {
            $image = $request->file('ruta_foto');
            $destinationPath = "images/alumnos";
            $imageName = $image->getClientOriginalName();
            $fechaHoraSeg = now()->format('Ymd_His');
            $fullPath = $destinationPath . '/' . $fechaHoraSeg . '_' . $imageName;
            $fullPath = $imageName;
            if (trim($alumno->ruta_foto) != "") {
                if (file_exists(public_path($destinationPath . '/' . $alumno->ruta_foto))) {
                    unlink(public_path($destinationPath . '/' . $alumno->ruta_foto));
                }
            }

            if (file_exists(public_path($fullPath))) {
                $alumno->ruta_foto = $fullPath;
            } else {
                $uploadSuccess = $image->move($destinationPath, $fechaHoraSeg . '_' . $imageName);
                // $alumno->ruta_foto = $fullPath;
                $alumno->ruta_foto = $fechaHoraSeg . '_' . $imageName;
            }
            $alumno->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Alumno registrado.');
            data_set($response, 'data', $alumno);
            data_set($response, 'alert_text', 'Alumno registrado');
            return response()->json($response, $response['status_code']);
        } else {
            $alumno->save();
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Petición satisfactoria | Alumno registrado.');
            data_set($response, 'data', $alumno);
            data_set($response, 'alert_text', 'Alumno registrado');
            return response()->json($response, $response['status_code']);
        }
    }

    public function storeBatchStudents(Request $request)
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
            Alumno::class,
            $validatedDataInsert,
            $validatedDataUpdate
        );
        if (!empty($validatedDataInsert)) {
            Alumno::insert($validatedDataInsert);
        }
        if (!empty($validatedDataUpdate)) {
            foreach ($validatedDataUpdate as $updateItem) {
                Alumno::where('numero', $updateItem['numero'])->update($updateItem);
            }
        }
        if ($alert_text) {
            $response = ObjectResponse::BadResponse($alert_text);
        } else {
            $response = ObjectResponse::CorrectResponse();
            data_set($response, 'message', 'Lista de Alumnos insertados correctamente.');
            data_set($response, 'alert_text', 'Todos los alumnos se insertaron correctamente.');
        }
        return response()->json($response, $response['status_code']);
    }
}
