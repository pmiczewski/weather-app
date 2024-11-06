<?php

namespace App\Services;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Models\Location;
use App\Models\User;

class LocationService
{
    private ForecastService $forecastService;
    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    public function list(User $user): array
    {
        return Location::query()
            ->where('user_id', $user->id)
            ->get()
            ->toArray();
    }

    public function show(int $locationId, User $user): array
    {
        return Location::query()
            ->with('forecasts')
            ->where('user_id', $user->id)
            ->where('id', $locationId)
            ->firstOrFail()
            ->toArray();
    }

    public function destroy(int $locationId, User $user): void
    {
        Location::query()
            ->where('user_id', $user->id)
            ->where('id', $locationId)
            ->delete();
    }

    public function store(LocationDto $dto, User $user): Location
    {
        $createdLocation = Location::create([
            ...$dto->toArray(),
            'user_id' => $user->id,
        ]);

        $locationForecastList = $this->forecastService->fetch($dto);
        foreach ($locationForecastList as $forecast) {
            $this->forecastService->store(
                new ForecastDto(...$forecast),
                $createdLocation->id
            );
        }

        return $createdLocation;
    }
}
