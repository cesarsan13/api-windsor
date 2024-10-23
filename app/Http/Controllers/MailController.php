<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SenderMail;
use Illuminate\Support\Facades\Validator;
use App\Models\ObjectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Http;
class MailController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'title' => 'required',
            'title2' => 'required',
            'body' => 'required',
            'view' => 'required',
            'email' => 'required|email',
        ];
        $messages = [
            'title.required' => 'El title es obligatorio.',
            'title2.required' => 'El title 2 es obligatorio.',
            'body.required' => 'El body es obligatorio.',
            'view.required' => 'El view es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico debe ser unico',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $alert_text = implode(" ", $errors);
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Información no válida');
            return response()->json($response, $response['status_code']);
        }
        $mailData = [
            'title' => $request->title,
            'title2' => $request->title2,
            'body' => $request->body,
            'view' => $request->view,
        ];

        Mail::to($request->email)->send(new SenderMail($mailData));

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Correo enviado exitosamente');
        return response()->json($response, $response['status_code']);
    }


    public function enviarNuevaContrasenaRegister(array $Data)
    {
        $rules = [
            'title' => 'required',
            'title2' => 'required',
            'body' => 'required',
            'view' => 'required',
            'email' => 'required|email',
        ];
        $messages = [
            'title.required' => 'El title es obligatorio.',
            'title2.required' => 'El title 2 es obligatorio.',
            'body.required' => 'El body es obligatorio.',
            'view.required' => 'El view es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico debe ser unico',
        ];

        $validator = Validator::make($Data, $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $alert_text = implode(" ", $errors);
            $response = ObjectResponse::BadResponse($alert_text);
            data_set($response, 'message', 'Información no válida');
            return response()->json($response, $response['status_code']);
        }

        $mailData = [
            'title' => $Data['title'],
            'title2' => $Data['title2'],
            'body' => $Data['body'],
            'view' => $Data['view'],
        ];
        Mail::to($Data['email'])->send(new SenderMail($mailData));

        $response = ObjectResponse::CorrectResponse();
        data_set($response, 'message', 'Correo enviado exitosamente');
        return response()->json($response, $response['status_code']);
    }

}
