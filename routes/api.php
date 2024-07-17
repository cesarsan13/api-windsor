<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipoCobroController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlumnoController;

Route::post('/login', [AuthController::class, 'login']);

Route::controller(TipoCobroController::class)->group(function () {
    Route::get("/tipo_cobro", "index");
    Route::get("/tipo_cobro/baja", "indexBaja");
    Route::get("/tipo_cobro/siguiente", "siguiente");
    Route::post('/tipo_cobro', 'store');
    Route::post('/tipo_cobro/update', 'update');
});

Route::middleware('auth:sanctum')->controller(ProductoController::class)->group(function () {
    Route::get('/product', 'showProduct');
    Route::get('/product/filter/{type}/{value}', 'productFilter');
    Route::get('/product/last', 'lastProduct');
    Route::get('/product/bajas', 'bajaProduct');
    Route::post('/product/save', 'storeProduct');
    Route::put('/product/update/{id}', 'updateProduct');
});

Route::controller(AlumnoController::class)->group(function () {
    Route::get('/students/imagen/{imagen}', 'showImageStudents');
    Route::get('/students', 'showAlumn');
    Route::get('/students/last', 'lastAlumn');
    Route::get('/students/bajas', 'bajaAlumn');
    Route::post('/students/save', 'storeAlumn');
    Route::post('/students/update/{id}', 'updateAlumn');
});
