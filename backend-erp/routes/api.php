<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;

Route::middleware('api')->group(function () {
    // CSRF
    Route::get('/sanctum/csrf-cookie', function () {
        return response()->json(['csrf' => csrf_token()]);
    });

    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

    // Dashboard (protected)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

    Route::apiResource('products', ProductController::class);

    Route::prefix('suppliers')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);    // Get all suppliers
    Route::post('/', [SupplierController::class, 'store']);    // Add supplier
});
});
