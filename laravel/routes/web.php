<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [
    TestController::class,
    'getProducts'
]);
Route::get('/products/{id}', [
    TestController::class,
    'getProductById'
]);
Route::delete('/products/{id}', [
    TestController::class,
    'deleteProductById'
]);

Route::patch('/products/{id}', [
    TestController::class,
    'updateProductById'
]);

Route::post('/products', [
    TestController::class,
    'createProducts'
]);
