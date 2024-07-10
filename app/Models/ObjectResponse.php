<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectResponse extends Model
{
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