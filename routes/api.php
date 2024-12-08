<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
});