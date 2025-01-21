<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

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
                $formattedMessage = implode(", ", $validated->messages()->all());
                $errors[] = "Error de validación: Número " . $item[$numero] . ": " . $formattedMessage;
                continue;
            }
            $exists = $Model::where('numero', '=', $item[$numero])->exists();
            $arrayToUse = $exists ? $validatedDataUpdate : $validatedDataInsert;
            $arrayToUse[] = $validated->validated();
        }
        $alert_text .= implode("<br><br>", $errors);
        return true;
    }
}
