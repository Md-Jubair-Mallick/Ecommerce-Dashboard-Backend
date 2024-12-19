<?php

use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** ---------> Only authenticated users can access <---------- */
Route::middleware(['auth:sanctum'])->group(function () {

    /** ---------> Only admin can access <---------- */
    Route::middleware(['role:admin'])->group(function () {

        /** ---------> Customer Routes <---------- */
        Route::controller(CustomerController::class)->group(function () {
            Route::post('/customers', 'store');
            Route::put('/customers/{id}', 'update');
            Route::delete('/customers/{id}', 'destroy');
        });


        /** ---------> Order Route <---------- */
        Route::put('/orders/{id}', [OrderController::class, 'update']);

        /** ---------> Auth Route <---------- */
        Route::post('/auth/register', [AuthController::class, 'register']);
    });

    /** ---------> Only admin and editor can access <---------- */
    Route::middleware(['role:admin,editor'])->group(function () {
        /** ---------> Product Routes <---------- */
        Route::controller(ProductController::class)->group(function () {
            Route::delete('/products/{id}', 'destroy');
            Route::post('/products', 'store');
            Route::put('/products/{id}', 'update');
        });

        /** ---------> Order Routes <---------- */
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders/{id}', 'show');
            Route::get('/orders', 'index');
        });

        /** ---------> Customer Routes <---------- */
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/customers', 'index');
            Route::get('/customers/{id}', 'show');
        });

        /** ---------> Review Routes <---------- */
        Route::controller(ReviewController::class)->group(function () {
            Route::put('reviews/{id}', 'update');
            Route::delete('/reviews/{id}', 'destroy');
        });
    });

    /** ---------> All roles can access <---------- */
    Route::middleware(['role:admin,editor,viewer'])->group(function () {
        /** ---------> Analytics Routes <---------- */
        Route::prefix('analytics')->group(function () {
            Route::get('/sales', [AnalyticsController::class, 'getSalesData']);
            Route::get('/revenue', [AnalyticsController::class, 'getRevenueData']);
            Route::get('/customers', [AnalyticsController::class, 'getCustomerGrowthData']);
        });
        
        /** ---------> Product Routes <---------- */
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::get('/reviews', [ReviewController::class, 'index']);

    });

    /** ---------> Auth Routes <---------- */
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
    });
});

/** ---------> Public Routes <---------- */
Route::post('/auth/login', [AuthController::class, 'login']);
