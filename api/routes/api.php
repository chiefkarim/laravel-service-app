<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return 'success';
})->middleware('auth:sanctum');

Route::apiResource('services', ServiceController::class)->middleware('auth:sanctum');
