<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Cuaca</title>
    <link rel="stylesheet" href="{{ asset('css/newStyle.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;600&display=swap" rel="stylesheet">
    <style>
        .weather-history-section {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #000;
            color: #fff;
        }

        .weather-history-summary {
            background-color: #0f111a;
            padding: 0.75rem 1rem;
            font-weight: bold;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s ease;
            color: #00bfff;
        }

        .weather-history-summary:hover {
            background-color: #000;
            color: #fff;
        }

        .weather-history-details {
            padding: 1rem;
            background-color: #0f111a;
            font-family: 'Nunito', sans-serif;
            line-height: 1.5;
            color: #fff;
        }

        .weather-day-section {
            margin-top: 0.5rem;
            border-top: 1px solid #ddd;
            padding-top: 0.5rem;
        }

        .weather-day-summary {
            font-weight: bold;
            color: #00bfff;
            cursor: pointer;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
        }

        .weather-day-summary:hover {
            color: #fff;
        }

        .weather-hour-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.5rem;
            padding: 0.5rem 0;
        }

        .weather-hour-box {
            background-color: #1e1e2e;
            padding: 0.5rem;
            border-radius: 5px;
            text-align: center;
        }

        .hour-time {
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .hour-temp {
            display: block;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">Cuaca</a>
            <ul class="navbar-menu">
                <li><a href="/cuaca">Beranda</a></li>
                <li><a href="/cuaca_db">Riwayat</a></li>
                <li><a href="/about">Tentang</a></li>
                <li><a href="/profile">Profile</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h2>Riwayat Cuaca</h2>
        @if($weatherHistory->isEmpty())
            <p>Tidak ada data cuaca yang tersedia.</p>
        @else
            <div class="content">
                @foreach($weatherHistory as $weather)
                    @php
                        $weatherData = is_array($weather->weather_data) ? $weather->weather_data : json_decode($weather->weather_data, true);
                        $grouped = collect($weatherData['hourly']['time'])->map(function($time, $i) use ($weatherData) {
                            return [
                                'time' => \Carbon\Carbon::parse($time),
                                'temp' => $weatherData['hourly']['apparent_temperature'][$i] ?? null,
                                'preci' => $weatherData['hourly']['precipitation_probability'][$i] ?? null,
                            ];
                        })->groupBy(fn($item) => $item['time']->translatedFormat('l'));
                    @endphp

                    <details class="weather-history-section">
                        <summary class="weather-history-summary">
                            {{ $weather->created_at }} - {{ $weather->city_name }}
                        </summary>
                        <div class="weather-history-details">
                            @foreach($grouped as $day => $items)
                                <details class="weather-day-section">
                                    <summary class="weather-day-summary">{{ $day }}</summary>
                                    <div class="weather-hour-grid">
                                        @foreach($items as $item)
                                            <div class="weather-hour-box">
                                                <div class="hour-time">{{ $item['time']->format('H:i') }}</div>
                                                <div class="hour-temp">{{ $item['temp'] }}°C</div>
                                                <div class="hour-temp">Peluang Hujan: {{ $item['preci'] }}%</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>