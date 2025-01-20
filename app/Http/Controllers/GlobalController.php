<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\ObjectResponse;

class GlobalController extends Controller
{
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table' => 'required|string',
        ]);
        if ($validator->fails()) {
            $alert_text = implode("<br>", $validator->messages()->all());
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Informacion no valida');
            return response()->json($response, $response['status_code']);
        }
        DB::table($request->table)->truncate();
        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Peticion satisfactoria');
        return response()->json($response, $response['status_code']);
    }
}
