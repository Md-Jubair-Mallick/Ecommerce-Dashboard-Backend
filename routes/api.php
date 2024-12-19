<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AnalyticsController;
// use App\Http\Middleware\RoleMiddleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/** ---------> Only authenticated users can access <---------- */
Route::middleware(['auth:sanctum'])->group(function () {

    /** ---------> Only admin can access <---------- */
    Route::middleware(['role:admin'])->group(function () {

        /** ---------> Product Routes <---------- */
        Route::controller(ProductController::class)->group(function () {
            Route::delete('/products/{id}', 'destroy');
            Route::post('/products', 'store');
            Route::put('/products/{id}', 'update');
        });

        /** ---------> Customer Routes <---------- */
        Route::controller(CustomerController::class)->group(function () {
            Route::post('/customer', 'store');
            Route::put('/customer/{id}', 'update');
            Route::delete('/customer/{id}', 'destroy');
        });

        /** ---------> Review Routes <---------- */
        Route::controller(ReviewController::class)->group(function () {
            Route::put('reviews/{id}', 'update');
            Route::delete('/reviews/{id}', 'destroy');
        });

        /** ---------> Order Route <---------- */
        Route::put('/orders/{id}', [OrderController::class, 'update']);

        /** ---------> Auth Route <---------- */
        Route::post('/auth/register', [AuthController::class, 'register']);
    });

    /** ---------> Only admin and editor can access <---------- */
    Route::middleware(['role:admin,editor'])->group(function () {
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

        /** ---------> Product Routes <---------- */
        Route::get('/products', [ProductController::class, 'index']);
    });

    /** ---------> All roles can access <---------- */
    Route::middleware(['role:admin,editor,viewer'])->group(function () {
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

/** ---------> Analytics Routes <---------- */
Route::prefix('analytics')->group(function () {
    Route::get('/sales', [AnalyticsController::class, 'getSalesData']);
    Route::get('/revenue', [AnalyticsController::class, 'getRevenueData']);
    Route::get('/customer-growth/{days?}', [AnalyticsController::class, 'getCustomerGrowthData']);
    Route::get('/daily-revenue/{days?}', [AnalyticsController::class, 'getDailyRevenue']);
    Route::get('/orders-per-category', [AnalyticsController::class, 'getOrdersPerCategory']);
});
