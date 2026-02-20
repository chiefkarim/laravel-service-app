<?php

use App\Http\Controllers\CurrentUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/health.php';

Route::get('/user', CurrentUserController::class)->middleware('auth:sanctum');

Route::apiResource('services', ServiceController::class)->except(['index'])->middleware('auth:sanctum');
Route::get('services', [ServiceController::class, 'index']);
Route::apiResource('service-requests', ServiceRequestController::class)->except(['store'])->middleware('auth:sanctum');
Route::post('service-requests', [ServiceRequestController::class, 'store']);

Route::apiResource('permissions', PermissionController::class)->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
