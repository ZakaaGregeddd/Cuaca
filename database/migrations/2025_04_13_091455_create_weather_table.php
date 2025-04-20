<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherTable extends Migration
{
    public function up()
    {
        Schema::create('cuaca', function (Blueprint $table) {
            $table->id();
            $table->string('city_name');
            $table->json('weather_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cuaca');
    }
        
}   