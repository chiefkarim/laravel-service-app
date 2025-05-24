<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('services', ServiceController::class, ['except' => ['create', 'edit']]);
Route::resource('service-requests', ServiceRequestController::class, ['except' => ['create', 'edit']]);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
