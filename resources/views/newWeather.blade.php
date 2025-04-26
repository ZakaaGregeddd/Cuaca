<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cuaca Hari Ini</title>
    <link rel="stylesheet" href="{{ asset('css/newStyle.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">Cuaca</a>
            <ul class="navbar-menu">
                <li><a href="/cuaca">Beranda</a></li>
                <li><a href="/cuaca_db">Riwayat</a></li>
                <li><a href="/about">Tentang</a></li>
            </ul>
        </div>
    </nav>
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
                        <span class="temperature">{{ $weather['hourly']['apparent_temperature'][0] ?? 'N/A' }}°C</span>
                        <p>Kelembaban: {{ $weather['hourly']['relative_humidity_2m'][0] ?? 'N/A' }}%</p>
                        <p>Curah Hujan: {{ $weather['hourly']['rain'][0] ?? 'N/A' }} mm</p>
                        <p>Peluang Hujan: {{ $weather['hourly']['precipitation_probability'][0] ?? 'N/A' }}%</p>
                    </div>
                @else
                    <p>Mohon masukkan kota.</p>
                @endif
            </div>

            @if($weather)
                @php
                $currentHour = \Carbon\Carbon::now()->format('H'); // Ambil jam saat ini
                $grouped = collect($weather['hourly']['time'])->map(function($time, $i) use ($weather) {
                    return [
                        'time' => \Carbon\Carbon::parse($time),
                        'temp' => $weather['hourly']['apparent_temperature'][$i] ?? null,
                        'preci' => $weather['hourly']['precipitation_probability'][$i] ?? null,
                    ];
                })->groupBy(fn($item) => $item['time']->translatedFormat('l'));
                @endphp

                <div class="forecast">
                    <h2>Perkiraan Cuaca Jam-Jam Sekitar</h2>

                    @php
                    $tomorrow = \Carbon\Carbon::now()->addDay()->translatedFormat('l'); // Ambil nama hari esok
                @endphp
                
                @foreach($grouped as $day => $items)
                    @if($day === \Carbon\Carbon::now()->translatedFormat('l') || $day === $tomorrow || \Carbon\Carbon::parse($day)->greaterThan(\Carbon\Carbon::now()))
                        <details class="weather-day-section">
                            <summary class="weather-day-summary">{{ $day }}</summary>
                            <div class="weather-hour-grid">
                                @foreach($items->filter(fn($i) => 
                                    ($day === \Carbon\Carbon::now()->translatedFormat('l') && $i['time']->greaterThan(\Carbon\Carbon::now()->addHour())) || 
                                    ($day === $tomorrow && $i['time']->format('H') >= 0) || 
                                    $day !== \Carbon\Carbon::now()->translatedFormat('l') && $day !== $tomorrow
                                ) as $item)
                                    <div class="weather-hour-box">
                                        <div class="hour-time">{{ $item['time']->format('H:i') }}</div>
                                        <div class="hour-temp">{{ $item['temp'] }}°C</div>
                                        <div class="hour-temp">Peluang Hujan: {{ $item['preci'] }}%</div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endif
                @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>