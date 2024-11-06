<?php

namespace App\Dtos;

readonly class ForecastDto extends Dto
{
    public function __construct(
        public string $date,
        public float $temperature,
        public float $min_temperature,
        public float $max_temperature,
        public string $weather_icon_id,
        public string $weather_description,
    ) {}
}
