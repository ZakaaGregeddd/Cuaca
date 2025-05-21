<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Weather;

class WeatherController extends Controller
{
    public function index()
    {
        return view('newWeather', ['weather' => null, 'cityName' => null, 'error' => null]);
    }

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
        $latestWeather = Weather::latest()->first();

        // Jika belum ada data dan tidak ada input kota, coba pakai IP user
        if (!$request->has('city') && !$latestWeather) {
            $ip = $request->ip();
            $location = Http::get("http://ip-api.com/json/{$ip}")->json();

            if (!isset($location['lat']) || !isset($location['lon'])) {
                return view('newWeather', [
                    'weather' => null,
                    'cityName' => null,
                    'error' => 'Gagal mendeteksi lokasi berdasarkan IP.'
                ]);
            }

            $lat = $location['lat'];
            $lon = $location['lon'];
            $city = $location['city'] ?? 'Lokasi Saat Ini';

            return $this->getAndStoreWeather($lat, $lon, $city);
        }

        return view('newWeather', [
            'weather' => $latestWeather?->weather_data,
            'cityName' => $latestWeather?->city_name,
            'error' => null,
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