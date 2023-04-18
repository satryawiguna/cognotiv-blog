<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
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
        Route::post('/create', [BlogCategoryController::class, "store"])->name('api.blogCategory.create');
        Route::put('/{id}/update', [BlogCategoryController::class, "update"])->name('api.blogCategory.update');
        Route::delete('/{id}/delete', [BlogCategoryController::class, "delete"])->name('api.blogCategory.delete');
    });

    Route::group(['prefix' => '/blog'], function () {
        Route::group(['prefix' => '/all'], function () {
            Route::get('/', [BlogController::class, "all"])->name('api.blog.all');
            Route::post('/search', [BlogController::class, "allSearch"])->name('api.blog.all.search');
            Route::post('/search/page', [BlogController::class, "allSearchPage"])->name('api.blog.all.search.page');
        });

        Route::get('/{id}', [BlogController::class, "show"])->name('api.blog.show');
        Route::post('/create', [BlogController::class, "store"])->name('api.blog.create');
        Route::put('/{id}/update', [BlogController::class, "update"])->name('api.blog.update');
        Route::delete('/{id}/delete', [BlogController::class, "delete"])->name('api.blog.delete');

        Route::group(['prefix' => '/{blogId}'], function () {
            Route::post('/comment/create', [CommentController::class, "store"])->name('api.blog.comment.create');
            Route::group(['prefix' => '/comment'], function () {
                Route::put('/{id}/update', [CommentController::class, "update"])->name('api.blog.comment.update');
                Route::delete('/{id}/delete', [CommentController::class, "delete"])->name('api.blog.comment.delete');
            });

            Route::post('/like-and-dislike', [BlogController::class, "likeAndDislike"])->name('api.blog.likeAndDislike');
        });
    });
});
