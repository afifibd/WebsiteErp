<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController; 


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class,'logout']);

    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('purchases', PurchaseController::class);

    // Report endpoint
    Route::get('/reports/sales',[ReportController::class,'sales']);
    Route::get('/reports/purchases',[ReportController::class,'purchases']);
});
