<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
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

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', [AuthController::class, "logout"])->name('api.auth.logout');
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/blog-category'], function () {
        Route::group(['prefix' => '/all'], function () {
            Route::get('/', [BlogCategoryController::class, "all"])->name('api.blogCategory.all');
            Route::post('/search', [BlogCategoryController::class, "allSearch"])->name('api.blogCategory.all.search');
            Route::post('/search/page', [BlogCategoryController::class, "allSearchPage"])->name('api.blogCategory.all.search.page');
        });

        Route::get('/{id}', [BlogCategoryController::class, "show"])->name('api.blogCategory.show');
        Route::post('/create', [BlogCategoryController::class, "create"])->name('api.blogCategory.create');
        Route::put('/update/{id}', [BlogCategoryController::class, "update"])->name('api.blogCategory.update');
        Route::delete('/delete/{id}', [BlogCategoryController::class, "delete"])->name('api.blogCategory.delete');
    });
});
