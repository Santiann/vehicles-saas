<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleImageController;
use Illuminate\Support\Facades\Route;

// Redirect root to vehicles
Route::get('/', function () {
    return redirect()->route('vehicles.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('vehicles.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vehicle CRUD routes
    Route::resource('vehicles', VehicleController::class);

    // Vehicle Image routes
    Route::post('/vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])
        ->name('vehicles.images.store');
    Route::patch('/vehicles/{vehicle}/images/{image}/cover', [VehicleImageController::class, 'setCover'])
        ->name('vehicles.images.cover');
    Route::delete('/vehicles/{vehicle}/images/{image}', [VehicleImageController::class, 'destroy'])
        ->name('vehicles.images.destroy');
});

require __DIR__.'/auth.php';
