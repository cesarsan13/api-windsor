<?php

use App\Http\Controllers\HorarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(HorarioController::class)->group(function (){
    Route::get('/horarios','getHorarios');
    Route::get('/horarios/baja','getHorariosBaja');
    Route::post('/horarios/post','postHorario');
    Route::post('/horarios/update','updateHorario');
    Route::get('/horarios/ultimo','ultimoHorario');
});