<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\GroupController;
use App\Models\trabajador;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('trabajador/auth', [TrabajadorController::class, 'authenticate']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [TrabajadorController::class, 'logout']);
    Route::get('me', [TrabajadorController::class, 'dataUser']);
    Route::get('trabajador', [TrabajadorController::class, 'index']);
});
Route::post('download', [DocumentController::class, 'getFile']);
Route::resource('trabajador', TrabajadorController::class);
Route::resource('comment', CommentController::class);
Route::resource('document', DocumentController::class);
Route::resource('group', GroupController::class);
