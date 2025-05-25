<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return 'success';
})->middleware('auth:sanctum');

Route::apiResource('services', ServiceController::class)->except(['index'])->middleware('auth:sanctum');
Route::get('services', [ServiceController::class, 'index']);
Route::apiResource('service-requests', ServiceRequestController::class)->except(['store'])->middleware('auth:sanctum');
Route::post('service-requests', [ServiceRequestController::class, 'store']);
