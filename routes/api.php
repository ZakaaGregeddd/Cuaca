<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/weather-history', [WeatherController::class, 'getWeatherHistory']);
