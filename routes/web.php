<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DocumentoController;
// use App\Http\Controllers\UsuarioController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::controller(HomeController::class)->group(function () {
    // Route::post('/home', 'store');
    // Route::get('/documento/u', '');
// });

// Route::controller(DocumentoController::class)->group(function () {
//     Route::get('/documento', 'index');
//     Route::get('/documento/{id}', 'show');
//     Route::get('/documento/usuario/{id}/', 'showByUserId');
// });

// Route::controller(UsuarioController::class)->group(function () {
//     Route::get('/usuario', 'index');
//     Route::get('/usuario/{id}', 'show');
//     Route::get('/usuario/{id}/detalles/{state?}', 'showDetails')->whereIn('state',['Firmado', 'Pendiente', 'Devuelto']);
// });



