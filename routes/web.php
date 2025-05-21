<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect('/cuaca');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cuaca', [WeatherController::class, 'index']);
    Route::post('/cuaca', [WeatherController::class, 'search']);

    Route::get('/cuaca_db', [WeatherController::class, 'showFromDatabase']);
    Route::post('/cuaca_db', [WeatherController::class, 'search']);

    Route::get('/history', [WeatherController::class, 'history']);

    Route::get('/about', [AboutController::class, 'index']);
});

require __DIR__.'/auth.php';
