<?php

namespace App\Http\Controllers;

use App\Dtos\LocationDto;
use App\Http\Requests\LocationRequest;
use App\Services\ForecastService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ForecastController extends Controller
{
    private ForecastService $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    public function fetch(LocationRequest $request)
    {
        $locationDto = new LocationDto(...$request->validated());
        $locationForecasts = $this->forecastService->fetch($locationDto);

        return Inertia::render('Forecast/Index', [
            'forecasts' => $locationForecasts,
        ]);
    }

    public function index()
    {
        return Inertia::render('Forecast/Index');
    }
}
