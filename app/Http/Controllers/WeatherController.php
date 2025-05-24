<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Weather;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    public function index()
    {
        return view('newWeather', ['weather' => null, 'cityName' => null, 'error' => null]);
    }

    // Get data korrdinat dari OpenStreetMap melalu data yang telah di post 
    public function search(Request $request)
    {
        $city = $request->input('city');

        // Ambil koordinat dari OpenStreetMap
        $geoUrl = "https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=" . urlencode($city);
        $geoResponse = Http::withHeaders([
            'User-Agent' => 'LaravelWeatherApp/1.0'
        ])->get($geoUrl);

        if ($geoResponse->failed() || count($geoResponse->json()) === 0) {
            return view('newWeather', ['weather' => null, 'cityName' => $city, 'error' => 'Kota tidak ditemukan.']);
        }

        $geoData = $geoResponse->json()[0];
        $lat = $geoData['lat'];
        $lon = $geoData['lon'];

        return $this->getAndStoreWeather($lat, $lon, $city);
    }


    
    public function showFromDatabase(Request $request)
    {
        $apiUrl = url('/api/weather-history');
        Log::info('Mengirim permintaan ke API: ' . $apiUrl); // Logging sebelum request

        $response = Http::timeout(60)->get($apiUrl); // Menambahkan timeout 60 detik

        if ($response->failed()) {
            Log::error('Gagal mengambil data dari API', ['status' => $response->status(), 'body' => $response->body()]); // Logging jika gagal
            return view('weather_blade_history', [
                'weatherHistory' => [],
                'error' => 'Gagal mengambil data dari API.'
            ]);
        }

        $weatherHistory = $response->json()['data'] ?? [];
        Log::info('Data berhasil diambil dari API', ['data' => $weatherHistory]); // Logging jika berhasil

        return view('weather_blade_history', [
            'weatherHistory' => $weatherHistory
        ]);
    }

    
    public function getWeatherHistory()
    {
        $weatherHistory = Weather::all();

        return response()->json([
            'success' => true,
            'data' => $weatherHistory
        ]);
    }



    
    private function getAndStoreWeather($lat, $lon, $city)
    {
        $weatherUrl = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&hourly=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation_probability,rain&timezone=auto";
        $weatherResponse = Http::get($weatherUrl);

        if ($weatherResponse->failed()) {
            return view('newWeather', ['weather' => null, 'cityName' => $city, 'error' => 'Gagal mengambil data cuaca.']);
        }

        $weatherData = $weatherResponse->json();

        $weather = Weather::create([
            'city_name' => $city,
            'weather_data' => $weatherData,
        ]);

        return view('newWeather', [
            'weather' => $weather->weather_data,
            'cityName' => $weather->city_name,
            'error' => null,
        ]);
    }

    public function history()
    {
        $weatherHistory = Weather::all();
        return view('weather_history', compact('weatherHistory'));
    }

    
}