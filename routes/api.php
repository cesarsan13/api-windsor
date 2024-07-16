<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipoCobroController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CajeroController;
use App\Http\Controllers\FormFactController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\HorarioController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->controller(TipoCobroController::class)->group(function () {
    Route::get("/tipo_cobro", "index");
    Route::get("/tipo_cobro/baja", "indexBaja");
    Route::get("/tipo_cobro/siguiente", "siguiente");
    Route::post('/tipo_cobro', 'store');
    Route::post('/tipo_cobro/update', 'update');
});

//Cajeros
Route::middleware('auth:sanctum')->controller(CajeroController::class)->group(function () {
    Route::post('/Cajero','PostCajeros');
    Route::post('/Cajero/UpdateCajeros','UpdateCajeros');
    Route::get('/Cajero/baja','indexBaja');
    Route::get("/Cajero","index");
    Route::get("/Cajero/siguiente", "siguiente");
});

//FormFact
Route::middleware('auth:sanctum')->controller(FormFactController::class)->group(function () {
    Route::post('/FormFact','PostFormFact');
    Route::post('/FormFact/UpdateFormFact','UpdateFormFact');
    Route::get('/FormFact/baja','indexBaja');
    Route::get("/FormFact","index");
    Route::get("/FormFact/siguiente", "siguiente");
});

Route::controller(ProductoController::class)->group(function () {
    Route::get('/product', 'showProduct');
    Route::get('/product/filter/{type}/{value}', 'productFilter');
    Route::get('/product/last', 'lastProduct');
    Route::get('/product/bajas', 'bajaProduct');
    Route::post('/product/save', 'storeProduct');
    Route::put('/product/update/{id}', 'updateProduct');
});

Route::middleware('auth:sanctum')->controller(ComentariosController::class)->group(function () {
    Route::get("/comentarios", "index");
    Route::get("/comentarios/baja", "indexBaja");
    Route::get("/comentarios/siguiente", "siguiente");
    Route::post('/comentarios', 'store');
    Route::post('/comentarios/update', 'update');
});




Route::controller(HorarioController::class)->group(function (){
    Route::get('/horarios','getHorarios');
    Route::get('/horarios/baja','getHorariosBaja');
    Route::post('/horarios/post','postHorario');
    Route::post('/horarios/update','updateHorario');
    Route::get('/horarios/ultimo','ultimoHorario');
});