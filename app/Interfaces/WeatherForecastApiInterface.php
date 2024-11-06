<?php

namespace App\Interfaces;

use App\Dtos\LocationDto;

interface WeatherForecastApiInterface
{
    public function fetch(LocationDto $dto): array;
}
