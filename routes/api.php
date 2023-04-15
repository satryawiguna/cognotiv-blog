<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [UserController::class, "register"])->name('api.register');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, "login"])->name('api.auth.login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, "logout"])->name('api.auth.logout');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
