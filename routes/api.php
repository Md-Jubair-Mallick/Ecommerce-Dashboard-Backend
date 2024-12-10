<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index'); // For listing all products
    Route::get('/products/{id}', 'show'); // For fetching a specific product
    Route::delete('/products/{id}', 'destroy'); // For deleting a product
    Route::post('/products', 'store'); // For creating a new product
    Route::put('/products/{id}', 'update');
});

Route::controller(OrderController::class)->group(function(){
    Route::get('/orders', 'index');
    Route::get('/orders/{id}', 'show');
    Route::put('/orders/{id}', 'update');
});

Route::controller(CustomerController::class)->group(function(){
    Route::get('/customers', 'index');
    Route::get('/customers/{id}', 'show');
    Route::post('/customers', 'store');
    Route::put('customers/{id}', 'update');
    Route::delete('/customers/{id}', 'destroy');
});

Route::controller(ReviewController::class)->group(function(){
    Route::get('/reviews', 'index');
    Route::put('reviews/{id}', 'update');
    Route::delete('/reviews/{id}', 'destroy');
});

Route::controller(AuthController::class)->group(function(){
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login')->middleware('guest');
    Route::post('/auth/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/auth/me', 'me')->middleware('auth:sanctum');
});