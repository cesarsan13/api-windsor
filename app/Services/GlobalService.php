<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GlobalService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function validateAndProcessData($numero, $data, $rules, $messages, &$alert_text, $Model, &$validatedDataInsert, &$validatedDataUpdate)
    {
        $errors = [];
        foreach ($data as $item) {
            $validated = Validator::make($item, $rules, $messages);
            if ($validated->fails()) {
                Log::info("Validation failed" . $item[$numero], $validated->messages()->all());
                $formattedMessage = implode(", ", $validated->messages()->all());
                $errors[] = "Error de validación: Número " . $item[$numero] . ": " . $formattedMessage;
                continue;
            }
            $exists = $Model::where($numero, '=', $item[$numero])->exists();
            if ($exists) {
                $validatedDataUpdate[] = $validated->validated();
            } else {
                $validatedDataInsert[] = $validated->validated();
            }
        }
        $alert_text .= implode("<br><br>", $errors);
        return true;
    }
    public function validateAndProcessDataDoblePK($campo1, $campo2, $data, $rules, $messages, &$alert_text, $Model, &$validatedDataInsert, &$validatedDataUpdate)
    {
        $errors = [];
        foreach ($data as $item) {
            $validated = Validator::make($item, $rules, $messages);
            if ($validated->fails()) {
                Log::info("Validation failed" . $item[$campo1]. " ". $item[$campo2], $validated->messages()->all());
                $formattedMessage = implode(", ", $validated->messages()->all());
                $errors[] = "Error de validación: Número " . $item[$campo1] . " ". $item[$campo2]. ": " . $formattedMessage;
                continue;
            }
            $exists = $Model::where($campo1, '=', $item[$campo1])->where($campo2, '=', $item[$campo2] )->exists();
            if ($exists) {
                $validatedDataUpdate[] = $validated->validated();
            } else {
                $validatedDataInsert[] = $validated->validated();
            }
        }
        $alert_text .= implode("<br><br>", $errors);
        return true;
    }
}
