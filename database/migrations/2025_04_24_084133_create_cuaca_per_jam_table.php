<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuacaPerJamTable extends Migration
{
    public function up()
    {
        Schema::create('cuaca_per_jam', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time')->unique();
            $table->float('temperature');
            $table->string('weather_description');
            $table->float('wind_speed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cuaca_per_jam');
    }
}
