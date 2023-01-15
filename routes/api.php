<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\LoginAndRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('board')->controller(BoardController::class)->group(function () {
        Route::get('/readAll', 'readAll');
        Route::get('/read/{id}', 'read');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');
    });

    Route::prefix('column')->controller(ColumnController::class)->group(function () {
        Route::get('/readAll', 'readAll');
        Route::get('/read/{id}', 'read');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');
    });

    Route::prefix('card')->controller(CardController::class)->group(function () {
        Route::get('/readAll', 'readAll');
        Route::get('/read/{id}', 'read');
        Route::post('/create', 'create');
        Route::post('/update', 'update');
        Route::post('/delete', 'delete');
    });
});

Route::post('/login', [LoginAndRegisterController::class, 'login']);
Route::post('/register', [LoginAndRegisterController::class, 'register']);
Route::post('/logout', [LoginAndRegisterController::class, 'logout']);
