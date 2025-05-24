<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;

Route::resource('services', ServiceController::class, ['except' => ['create', 'edit']]);
Route::resource('service-requests', ServiceRequestController::class, ['except' => ['create', 'edit']]);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
