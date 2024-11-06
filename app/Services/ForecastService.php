<?php

namespace App\Services;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Interfaces\WeatherForecastApiInterface;
use App\Models\Forecast;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ForecastService
{
    private WeatherForecastApiInterface $forecastApi;
    public function __construct(WeatherForecastApiInterface $forecastApi)
    {
        $this->forecastApi = $forecastApi;
    }

    public function fetch(LocationDto $dto): array
    {
        $cacheKey = "$dto->city,$dto->state";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $forecastList = $this->forecastApi->fetch($dto);
        $forecastList = $this->getTodayMostRecentAndNextThreeDaysForecast($forecastList);

        Cache::put($cacheKey, $forecastList, now()->addHour());

        return $forecastList;
    }

    public function store(ForecastDto $dto, int $locationId): Forecast
    {
       return Forecast::create([
           ...$dto->toArray(),
           'location_id' => $locationId,
       ]);
    }

    private function getTodayMostRecentAndNextThreeDaysForecast(array $forecastList): array
    {
        return collect($forecastList)
            ->groupBy(function (ForecastDto $item) {
                return Carbon::parse($item->date)->toDateString();
            })
            ->take(4)
            ->map(function (Collection $groupedByDateForecasts) {
                return $groupedByDateForecasts->first();
            })
            ->values()
            ->toArray();
    }
}
