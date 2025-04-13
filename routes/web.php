<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cuaca', [WeatherController::class, 'index']);

Route::post('/cuaca', [WeatherController::class, 'search']);