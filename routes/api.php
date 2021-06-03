<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

function generateCommonAuthRoutes($scopeMiddleware)
{
    Route::post("register", [AuthController::class, 'register'])->name('register');
    Route::post("login", [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:sanctum', $scopeMiddleware])->group(function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::put('users/profile', [AuthController::class, 'updateInfo']);
        Route::put('users/password', [AuthController::class, 'updatePassword']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
}

//Admin

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    generateCommonAuthRoutes('scope:admin');

    Route::middleware(['auth:sanctum', 'scope:admin'])->group(function () {

        Route::get('/ambassadors', [AmbassadorController::class, 'index']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/users/{id}/links', [LinkController::class, 'index']);

        Route::apiResource('products', ProductController::class);
    });
});

//Ambassadors

Route::group(['prefix' => 'ambassador', 'as' => 'ambassador.'], function () {
    generateCommonAuthRoutes('scope:ambassador');

    Route::get('products/frontend', [ProductController::class, 'frontent']);
    Route::get('products/backend', [ProductController::class, 'backend']);

    Route::middleware(['auth:sanctum', 'scope:ambassador'])->group(function () {
        Route::get('/stats', [StatsController::class, 'index']);
        Route::get('/rankings', [StatsController::class, 'rankings']);
    });
});
