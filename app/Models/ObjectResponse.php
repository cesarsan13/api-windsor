<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectResponse extends Model
{
     public static function Rep_Dos_Sel($tamaño) {
        $rep_dos_sel=[];
        $tamaño_ciclo= $tamaño +1;
        for ($i=1; $i < $tamaño_ciclo ; $i++) { 
            $objeto = new \stdClass();
            $objeto->Num_Renglon = $i;
            $objeto->Numero_1 = 0;
            $objeto->Nombre_1 = "";
            $objeto->Año_Nac_1 = 0; 
            $objeto->Mes_Nac_1 = 0; 
            $objeto->Telefono_1 = 0; 
            $objeto->Numero_2 = 0;
            $objeto->Nombre_2 = "";
            $objeto->Año_Nac_2 = 0; 
            $objeto->Mes_Nac_2 = 0; 
            $objeto->Telefono_2 = 0; 
            $rep_dos_sel[] = $objeto;          
        }
        return $rep_dos_sel;
    }
    public static function PrepHorario($lista,$rep_dos_sel,$num_horario){
        $length =count($lista);
        for ($i=0; $i < $length; $i++) { 
            if (isset($rep_dos_sel[$i])){

                $objeto = $rep_dos_sel[$i];
                $alumno = $lista[$i];
                if($num_horario==1){
                        $objeto->Numero_1 = $alumno['id'];
                        $objeto->Nombre_1 = $alumno['a_nombre'];
                        $objeto->Año_Nac_1 = $alumno['fecha_nac'];
                        $objeto->Mes_Nac_1 = $alumno['fecha_nac'];
                }else{
                        $objeto->Numero_2 = $alumno['id'];
                        $objeto->Nombre_2 = $alumno['a_nombre'];
                        $objeto->Año_Nac_2 = $alumno['fecha_nac'];
                        $objeto->Mes_Nac_2 = $alumno['fecha_nac'];
                }
             }
            $rep_dos_sel[$i] = $objeto;
        }
    return $rep_dos_sel;
    }
    public static function CorrectResponse() {
        $response = [
            "status_code" => 200,
            "status" => true,
            "message" => "petición satisfactoria.",
            "alert_title" => "EXITO!",
            "alert_icon" => "success",
            "alert_text" => "",
            "data" => [],
        ];
        return $response;
    }

    public static function BadResponse($alert_text) {
        $response = [
            "status_code" => 400,
            "status" => false,
            "message" => "Informacion Invalida",
            "alert_icon" => "info",
            "alert_title" => "Bad Request.",
            "alert_text" => $alert_text,
            "data" => [],
        ];
        return $response;
    }
    

    public static function DefaultResponse() {
        $response = [
            "status_code" => 500,
            "status" => false,
            "message" => "no se logro completar la petcion.",
            "alert_icon" => "informative",
            "alert_title" => "Lo sentimos.",
            "alert_text" => "Hay un problema con el servidor. Intenete más tarde.",
            "data" => [],
        ];
        return $response;
    }

    public static function CatchResponse($message) {
        

        $response = [
            "status_code" => 400,
            "status" => false,
            "message" => $message ?? "Ocurrio un error, verifica tus datos.",
            "alert_icon" => "error",
            "alert_title" => "Oppss!",
            "alert_text" => "Algo salio mal, verifica tus datos.",
            "data" => [],
        ];
        return $response;
    }
}