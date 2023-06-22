<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiskController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

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

Route::controller(DocumentController::class)->group(function () {
    Route::get('/document', 'index');
    Route::get('/document/{id}', 'show');
    Route::get('/document/user/{id}/', 'showByUserId');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'index');
    Route::get('/user/details', 'showAllUserDetails'); 
    Route::get('/user/{id}', 'show');
    Route::get('/user/{id}/details', 'showDetails');
    Route::get('/user/{id}/documents/{state?}', 'userDocuments')->whereIn('state', ['completed', 'pending', 'returned']);
});

Route::controller(TransactionController::class)->group(function () {
    Route::get('/transactions', 'index');
    Route::get('/transactions/{id}', 'get');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index');
    Route::get('/reports', 'productReports');
});

Route::controller(DiskController::class)->group(function() {
    Route::get('/sended/document/{id}', 'b64ObjectAzure');
    Route::get('/sended/document/{id}/certificate', 'getStorage');
});

// Route::get('/sended/document/{id}', [DiskController::class ,'b64ObjectAzure']);
// Route::controller(DiskController::class)->group(function(){
//     Route::get('/')
// })
