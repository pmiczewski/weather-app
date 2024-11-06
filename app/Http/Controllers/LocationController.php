<?php

namespace App\Http\Controllers;

use App\Dtos\LocationDto;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LocationController extends Controller
{
    private LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function index(Request $request)
    {
        $locations = $this->locationService->list($request->user());

        return Inertia::render('Location/Index', [
            'locations' => $locations,
        ]);
    }

    public function show(Request $request, int $locationId)
    {
        $location = $this->locationService->show($locationId, $request->user());

        return Inertia::render('Location/Show', [
            'location' => $location,
        ]);
    }

    public function destroy(Request $request, int $locationId)
    {
        $this->locationService->destroy($locationId, $request->user());

        return Redirect::back();
    }

    public function store(LocationRequest $request)
    {
        Gate::authorize('create', Location::class);

        $locationDto = new LocationDto(...$request->validated());
        $this->locationService->store($locationDto, $request->user());

        return Redirect::back();
    }
}
