<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

require __DIR__.'/health.php';

Route::get('/user', function (Request $request) {
    $user = $request->user()->load('permissions');

    return $user;
})->middleware('auth:sanctum');

Route::apiResource('services', ServiceController::class)->except(['index'])->middleware('auth:sanctum');
Route::get('services', [ServiceController::class, 'index']);
Route::apiResource('service-requests', ServiceRequestController::class)->except(['store'])->middleware('auth:sanctum');
Route::post('service-requests', [ServiceRequestController::class, 'store']);

Route::apiResource('permissions', PermissionController::class)->middleware('auth:sanctum');
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
