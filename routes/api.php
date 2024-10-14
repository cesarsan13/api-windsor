<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AdeudosPendientesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AsignaturasController;
use App\Http\Controllers\TipoCobroController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CajeroController;
use App\Http\Controllers\FormFactController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\AlumnosPorClaseController;
use App\Http\Controllers\CobranzaController;
use App\Http\Controllers\CobranzaProductosController;
use App\Http\Controllers\DocumentosCobranzaController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\FacturasFormatoController;
use App\Http\Controllers\Pagos1Controller;
use App\Http\Controllers\RepDosSelController;
use App\Http\Controllers\ReportesController;
use Database\Seeders\DocumentosCobranzaSeeder;
use App\Http\Controllers\ProcesosController;
use App\Http\Controllers\ProfesoresController;

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
    Route::post('/Cajero', 'PostCajeros');
    Route::post('/Cajero/UpdateCajeros', 'UpdateCajeros');
    Route::get('/Cajero/baja', 'indexBaja');
    Route::get("/Cajero", "index");
    Route::get("/Cajero/siguiente", "siguiente");
});

//FormFact
Route::middleware('auth:sanctum')->controller(FormFactController::class)->group(function () {
    Route::post('/FormFact', 'PostFormFact');
    Route::post('/FormFact/UpdateFormFact', 'UpdateFormFact');
    Route::get('/FormFact/baja', 'indexBaja');
    Route::get("/FormFact", "index");
    Route::get("/FormFact/siguiente", "siguiente");
});

//Asignaturas
Route::middleware('auth:sanctum')->controller(AsignaturasController::class)->group(function () {
    Route::get('/subject/filter/{type}/{value}', 'subjectFilter');
    Route::get('/subject', 'showSubject');
    Route::get('/subject/last', 'lastSubject');
    Route::post('/subject/save', 'storeSubject');
    Route::get('/subject/bajas', 'bajaSubject');
    Route::put('/subject/update/{numero}', 'updateSubject');
});

// Route::middleware('auth:sanctum')->controller(RepDosSelController::class)->group(function () {
//     Route::post('/RepDosSel/UpdateRepDosSel', 'UpdateRepDosSel');
//     Route::get("/RepDosSel/siguiente", "siguiente");
// });

Route::middleware('auth:sanctum')->controller(ProductoController::class)->group(function () {
    Route::get('/product', 'showProduct');
    Route::get('/product/filter/{type}/{value}', 'productFilter');
    Route::get('/product/last', 'lastProduct');
    Route::get('/product/bajas', 'bajaProduct');
    Route::post('/product/save', 'storeProduct');
    Route::put('/product/update/{numero}', 'updateProduct');
});

Route::middleware('auth:sanctum')->controller(AlumnoController::class)->group(function () {
    Route::get('/students/datasex/', 'dataAlumSex');
    Route::get('/students/imagen/{imagen}', 'showImageStudents');
    Route::get('/students', 'showAlumn');
    Route::get('/students/last', 'lastAlumn');
    Route::post('/students/report', 'getReportAlumn');
    Route::post('/students/report/AltaBaja', 'getReportAltaBajaAlumno');
    Route::get('/students/bajas', 'bajaAlumn');
    Route::post('/students/save', 'storeAlumn');
    Route::post('/students/update/{numero}', 'updateAlumn');
    Route::put('/students-cambio-id', 'changeIdAlumno');
    Route::get('/students/cumpleanos-mes', 'cumpleanerosDelMes');
    Route::put('/students/cambio-ciclo', 'cambiarCicloAlumnos');
});

Route::middleware('auth:sanctum')->controller(ComentariosController::class)->group(function () {
    Route::get("/comentarios", "index");
    Route::get("/comentarios/baja", "indexBaja");
    Route::get("/comentarios/siguiente", "siguiente");
    Route::post('/comentarios', 'store');
    Route::post('/comentarios/update', 'update');
});
Route::middleware('auth:sanctum')->controller(FacturasFormatoController::class)->group(function () {
    Route::get("/facturasformato/{id}", "index");
    Route::post("/facturasformato/update", "updateFormato");
});



Route::middleware('auth:sanctum')->controller(HorarioController::class)->group(function () {
    Route::get('/horarios', 'getHorarios');
    Route::get('/horarios/baja', 'getHorariosBaja');
    Route::post('/horarios/post', 'postHorario');
    Route::post('/horarios/update', 'updateHorario');
    Route::get('/horarios/ultimo', 'ultimoHorario');
    Route::get('/horarios/alumnosxhorario', 'getAlumnosXHorario');
});

Route::middleware('auth:sanctum')->controller(AlumnosPorClaseController::class)->group(function () {
    Route::get('/AlumnosPC/HorariosAPC', 'getHorariosAPC');
    Route::get('/AlumnosPC/Lista/{idHorario1}/{idHorario2}/{orden}', 'UpdateRepDosSel');
    Route::get('/AlumnosPC/Lista/{idHorario}/{orden}', 'getListaHorariosAPC');
});

Route::post('/cobranza', [CobranzaController::class, 'PDF'])->middleware('auth:sanctum');

Route::controller(DocumentosCobranzaController::class)->group(function () {
    Route::get('/documentoscobranza/{fecha}/{grupo?}', 'imprimir')->middleware('auth:sanctum');
    Route::get('/documentoscobranza', 'get_Grupo_Cobranza')->middleware('auth:sanctum');
    Route::put('/documentoscobranza/grupo', 'poner_Grupo_Cobranza')->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->controller(CobranzaProductosController::class)->group(function () {
    Route::get('/cobranzaProducto/{fecha1}/{fecha2}/{articulo?}/{artFin?}', 'infoDetallePedido');
    Route::get('/cobranzaProductos/{porNombre?}', 'infoTrabRepCobr');
    Route::post('/cobranzaProducto/insert', 'insertTrabRepCobr');
});


Route::middleware('auth:sanctum')->controller(ReportesController::class)->group(function () {
    Route::post("/reportes/rep_femac_13", "getAlumnosPorClaseSemanal");
    Route::post("/reportes/rep_femac_8_anexo_1", "getRelaciondeRecibos");
    Route::post("/reportes/rep_femac_2", "getAlumnosPorClase");
    Route::post("/reportes/rep_femac_3", "getAlumnosPorMes");
    Route::post("/reportes/rep_becas", "getBecas");
    Route::post("/reportes/rep_femac_11_anexo_3", "getCobranzaAlumno");
    Route::post("/reportes/rep_femac_10_anexo_2", "getEstadodeCuenta");
    Route::post("/reportes/rep_femac_9_anexo_4", "getRelaciondeFacturas");
    Route::get("/reportes/rep_inscritos", "getConsultasInscripcion");
});

Route::middleware('auth:sanctum')->controller(Pagos1Controller::class)->group(function () {
    Route::post("/pagos1/validar-clave-cajero", "validarClaveCajero");
    Route::post("/pagos1/buscar-articulo", "buscarArticulo");
    Route::post("/pagos1/busca-documentos", "buscaDocumentosCobranza");
    Route::post("/pagos1/guarda-documentos", "guardarDocumentoCobranza");
    Route::post("/pagos1/busca-propietario", "buscaPropietario");
    Route::post("/pagos1/guardar-detalle-pedido", "guardarDetallePedido");
    Route::post("/pagos1/guarda-EncabYCobrD", "guardaEcabYCobrD");
    Route::post("/pagos1/busca-doc-cobranza", "obtenerDocumentosCobranza");
});

Route::middleware('auth:sanctum')->controller(EstadisticasController::class)->group(function () {
    Route::get('/estadisticas-total-home', 'obtenerEstadisticas');
    Route::get('/estadisticas-cajero-mes-home', 'mesActualCajeros');
});

Route::middleware('auth:sanctum')->controller(ProcesosController::class)->group(function () {
    Route::post('/cartera/proceso', 'procesoCartera');
    Route::get('/cartera/actualizar', 'actualizarDocumentoCartera');
    Route::post('/cancelacion-recibo', 'cancelarRecibo');
});

Route::middleware('auth:sanctum')->controller(ProcesosController::class)->group(function () {
    Route::post('/cartera/proceso', 'procesoCartera');
    Route::get('/cartera/actualizar', 'actualizarDocumentoCartera');
    Route::post('/cancelacion-recibo', 'cancelarRecibo');
});

Route::middleware('auth:sanctum')->controller(ProfesoresController::class)->group(function () {
    Route::get('/profesores/index', 'index');
    Route::get('/profesores/index-baja', 'indexBaja');
    Route::get('/profesores/siguiente', 'siguiente');
    Route::post('/profesores/update', 'update');
    Route::post('/profesores/save', 'save');
});

Route::post('/documentosCobranza', [AdeudosPendientesController::class, 'getDetallePedidos'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->controller(ActividadController::class)->group(function () {
    Route::get('/actividades/get','getActividades');
    Route::get('/actividades/baja','getActividadesBaja');
    Route::post('/actividades/post','postActividad');
    Route::post('/actividades/update','updateActividad');
    Route::post('/actividades/ultimaSecuencia','ultimaSecuencia');
});