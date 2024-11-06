<?php

use App\Models\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->datetime('date');
            $table->float('temperature');
            $table->float('min_temperature');
            $table->float('max_temperature');
            $table->string('weather_icon_id');
            $table->string('weather_description');
            $table->foreignIdFor(Location::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
