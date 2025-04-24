<?php

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/cuaca', [WeatherController::class, 'index']);
Route::post('/cuaca', [WeatherController::class, 'search']);

Route::get('/cuaca_db', [WeatherController::class, 'showFromDatabase']);
Route::post('/cuaca_db', [WeatherController::class, 'search']);

Route::get('/about', [AboutController::class, 'index']);



Route::get('/testnew', function () {
    return view('newWeather');
});
