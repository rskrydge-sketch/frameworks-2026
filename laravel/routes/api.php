<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [
    AuthController::class,
    'register'
]);
Route::post('/login', [
    AuthController::class,
    'login'
]);


Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::post('/refresh', [
        AuthController::class,
        'refresh'
    ]);

    Route::get('/products', [
        TestController::class,
        'getProducts'
    ]);
    Route::get('/products/{id}', [
        TestController::class,
        'getProductById'
    ]);

    Route::middleware('can:admin')->group(function () {
        Route::post('/products', [
            TestController::class,
            'createProducts'
        ]);
        Route::patch('/products/{id}', [
            TestController::class,
            'updateProductById'
        ]);
        Route::delete('/products/{id}', [
            TestController::class,
            'deleteProductById'
        ]);
    });
});
