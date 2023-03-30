<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DiskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(DocumentoController::class)->group(function () {
    Route::get('/documento', 'index');
    Route::get('/documento/{id}', 'show');
    Route::get('/documento/usuario/{id}/', 'showByUserId');
});

Route::controller(UsuarioController::class)->group(function () {
    Route::get('/usuario', 'index');
    Route::get('/usuario/{id}', 'show');
    Route::get('/usuario/{id}/detalles/{state?}', 'showDetails')->whereIn('state',['Firmado', 'Pendiente', 'Devuelto']);
});

Route::get('/sended/document/{id}', [DiskController::class ,'downloadObject']);
// Route::controller(DiskController::class)->group(function(){
//     Route::get('/')
// })
