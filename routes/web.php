<?php

use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('location.index');

    Route::get('/location/{id}', [LocationController::class, 'show'])->name('location.show');
    Route::delete('/location/{id}', [LocationController::class, 'destroy'])->name('location.destroy');
    Route::post('/location', [LocationController::class, 'store'])->name('location.store');

    Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast.index');
    Route::post('/forecast', [ForecastController::class, 'fetch'])->name('forecast.fetch');
});

require __DIR__.'/auth.php';
