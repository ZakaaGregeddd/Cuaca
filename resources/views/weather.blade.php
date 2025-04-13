<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prakiraan Cuaca</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dark-mode">

    <div class="container">
        <h1 class="title">Prakiraan Cuaca - {{ $cityName ?? 'Tidak Diketahui' }}</h1>

        <form method="POST" action="/cuaca" class="search-form">
            @csrf
            <input type="text" name="city" placeholder="Cari nama kota..." required>
            <button type="submit">Cari</button>
        </form>

        @if(isset($error))
            <p class="error">{{ $error }}</p>
        @endif

        @if($weather)
            <div class="weather-grid">
                @foreach($weather['hourly']['time'] as $index => $time)
                    <div class="weather-card">
                        <h2>{{ \Carbon\Carbon::parse($time)->format('H:i') }}</h2>
                        <p>Suhu: {{ $weather['hourly']['temperature_2m'][$index] }}°C</p>
                        <p>Kelembaban: {{ $weather['hourly']['relative_humidity_2m'][$index] }}%</p>
                        <p>Curah Hujan: {{ $weather['hourly']['rain'][$index] }} mm</p>
                        <p>Peluang Hujan: {{ $weather['hourly']['precipitation_probability'][$index] }}%</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
