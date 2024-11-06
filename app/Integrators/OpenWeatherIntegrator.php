<?php

namespace App\Integrators;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Interfaces\WeatherForecastApiInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class OpenWeatherIntegrator implements WeatherForecastApiInterface
{
    private const UNIT_OF_MEASUREMENT = 'imperial';

    public function fetch(LocationDto $dto): array
    {
        $cityAndStateCombination = "$dto->city,$dto->state";

        $response = Http::get(Config::get('services.open_weather.url'), [
            'q' => $cityAndStateCombination,
            'units' => self::UNIT_OF_MEASUREMENT,
            'appid' => Config::get('services.open_weather.key'),
        ]);
        $response->throw();

        return $this->parse($response->json());
    }

    private function parse(array $response): array
    {
        $forecastList = [];
        foreach (Arr::get($response, 'list') as $rawForecast) {
            $parsedForecast = [
                'date' => Arr::get($rawForecast, 'dt_txt'),
                'temperature' => Arr::get($rawForecast, 'main.temp'),
                'min_temperature' => Arr::get($rawForecast, 'main.temp_min'),
                'max_temperature' => Arr::get($rawForecast, 'main.temp_max'),
                'weather_icon_id' => Arr::get($rawForecast, 'weather.0.icon'),
                'weather_description' => Arr::get($rawForecast, 'weather.0.main'),
            ];
            $forecastDto = new ForecastDto(...$parsedForecast);
            $forecastList[] = $forecastDto;
        }

        return $forecastList;
    }
}
