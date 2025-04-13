<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Cuaca</title>
</head>
<body>
    <h1>Riwayat Cuaca</h1>
    <table>
        <thead>
            <tr>
                <th>Kota</th>
                <th>Data Cuaca</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($weatherHistory as $weather)
                <tr>
                    <td>{{ $weather->city_name }}</td>
                    <td>{{ json_encode($weather->weather_data) }}</td>
                    <td>{{ $weather->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>