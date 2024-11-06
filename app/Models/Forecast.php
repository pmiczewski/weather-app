<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{
    /** @use HasFactory<\Database\Factories\ForecastFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'temperature',
        'min_temperature',
        'max_temperature',
        'weather_icon_id',
        'weather_description',
        'location_id',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
