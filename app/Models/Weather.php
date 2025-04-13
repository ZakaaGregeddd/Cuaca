<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;

    protected $fillable = ['city_name', 'weather_data'];

    protected $casts = [
        'weather_data' => 'array', // Mengonversi kolom JSON menjadi array
    ];
}