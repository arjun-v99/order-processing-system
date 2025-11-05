<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'authenticateLogin']);

Route::get('/dashboard', [DashboardController::class, 'getOrdersSummmary'])->middleware('auth:sanctum');
Route::post('/orders/{orderId}', [OrderController::class, 'processOrder'])->where('orderId', '[0-9]+')->middleware('auth:sanctum');
