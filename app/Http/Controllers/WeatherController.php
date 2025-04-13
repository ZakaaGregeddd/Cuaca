<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather', ['weather' => null, 'cityName' => null]);
    }

    public function search(Request $request)
    {
        $city = $request->input('city');

        //Ambil koordinat kota dari Nominatim OpenStreetMap
        // $geoUrl = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($city);
        // $geoResponse = Http::get($geoUrl);
        $geoUrl = "https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=" . urlencode($city);

        $geoResponse = Http::withHeaders([
            'User-Agent' => 'LaravelWeatherApp/1.0' // Penting agar tidak diblok
        ])->get($geoUrl);


        if ($geoResponse->failed() || count($geoResponse->json()) === 0) {
            return view('weather', ['weather' => null, 'cityName' => $city, 'error' => 'Kota tidak ditemukan.']);
        }

        $geoData = $geoResponse->json()[0];
        $lat = $geoData['lat'];
        $lon = $geoData['lon'];
        //baca

        //Panggil API cuaca Open-Meteo
        $weatherUrl = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&hourly=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation_probability,rain,temperature_120m&timezone=auto";
        $weatherResponse = Http::get($weatherUrl);

        if ($weatherResponse->failed()) {
            return view('weather', ['weather' => null, 'cityName' => $city, 'error' => 'Gagal mengambil data cuaca.']);
        }

        $weatherData = $weatherResponse->json();

        return view('weather', [
            'weather' => $weatherData,
            'cityName' => $city,
            'error' => null,
        ]);
    }
}

