<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cuaca Hari Ini</title>
    <link rel="stylesheet" href="{{ asset('css/newStyle.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <form class="search" method="POST" action="/cuaca_db">
            @csrf
            <input type="text" name="city" placeholder="Cari kota...">
            <button type="submit">Cari</button>
        </form>
        
        @if($error)
            <p style="color: red;">{{ $error }}</p>
        @endif

        <div class="content">
            <div class="today">
                <h2>Hari Ini - {{ $cityName }}</h2>
                @if($weather)
                    <div class="weather-info">
                        <span class="temperature">{{ $weather['hourly']['temperature_2m'][0] ?? 'N/A' }}°C</span>
                        <p>Kelembaban: {{ $weather['hourly']['relative_humidity_2m'][0] ?? 'N/A' }}%</p>
                        <p>Curah Hujan: {{ $weather['hourly']['rain'][0] ?? 'N/A' }} mm</p>
                        <p>Peluang Hujan: {{ $weather['hourly']['precipitation_probability'][0] ?? 'N/A' }}%</p>
                    </div>
                @else
                    <p>Belum ada data cuaca di database.</p>
                @endif
            </div>

            @if($weather)
                @php
                $grouped = collect($weather['hourly']['time'])->map(function($time, $i) use ($weather) {
                    return [
                        'time' => \Carbon\Carbon::parse($time), // Gunakan namespace lengkap
                        'temp' => $weather['hourly']['temperature_2m'][$i] ?? null,
                    ];
                })->groupBy(fn($item) => $item['time']->translatedFormat('l'));
                @endphp

                <div class="forecast">
                    <h2>Perkiraan Cuaca Jam-Jam ke Depan</h2>

                    @foreach($grouped as $day => $items)
                        <details class="weather-day-section">
                            <summary class="weather-day-summary">{{ $day }}</summary>
                            <div class="weather-hour-grid">
                                @foreach($items->filter(fn($i) => in_array($i['time']->format('H'), ['06','09','12','15','18','21'])) as $item)
                                    <div class="weather-hour-box">
                                        <div class="hour-time">{{ $item['time']->format('H:i') }}</div>
                                        <div class="hour-temp">{{ $item['temp'] }}°C</div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
