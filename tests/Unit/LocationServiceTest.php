<?php

namespace Tests\Unit;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Models\Forecast;
use App\Models\Location;
use App\Models\User;
use App\Services\ForecastService;
use App\Services\LocationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    private LocationService $locationService;
    private ForecastService $forecastServiceMock;
    protected function setUp(): void
    {
        parent::setUp();
        $this->forecastServiceMock = $this->createMock(ForecastService::class);
        $this->locationService = new LocationService($this->forecastServiceMock);
    }

    public function testListShouldReturnEveryLocationFromUser(): void
    {
        $user = User::factory()
            ->has(Location::factory()->count(3))
            ->create();

        $result = $this->locationService->list($user);

        $this->assertCount(3, $result);

        $everyLocationIsFromUser = collect($result)
            ->every(fn (array $location) => Arr::get($location, 'user_id') === $user->id);
        $this->assertTrue($everyLocationIsFromUser);
    }

    public function testShowShouldReturnLocationWithForecasts(): void
    {
        $user = User::factory()
            ->has(Location::factory())
            ->create();
        $location = $user->locations->first();

        $expectedLocation = [
            'id' => $location->id,
            'city' => $location->city,
            'state' => $location->state,
            'user_id' => $user->id,
            'forecasts' => [],
        ];

        $result = $this->locationService->show($location->id, $user);
        $result = Arr::except($result, ['created_at', 'updated_at']);

        $this->assertEquals($expectedLocation, $result);
    }

    public function testShowShouldThrowExceptionWhenFailToRetrieveLocation(): void
    {
        $user = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        $this->locationService->show(1, $user);
    }

    public function testDestroyShouldSoftDeleteFromDatabase(): void
    {
        $user = User::factory()
            ->has(Location::factory())
            ->create();

        $location = $user->locations->first();

        $this->locationService->destroy($location->id, $user);

        $this->assertDatabaseMissing($location->getTable(), ['id' => $location->id]);
    }

    public function testStoreShouldStoreLocationInDatabaseCallForecastServiceFetchThenCallStore(): void
    {
        $user = User::factory()->create();
        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');

        $responseMock = [
            [
                'date' => '2024-11-06 00:00:00',
                'temperature' => 74.32,
                'min_temperature' => 71.28,
                'max_temperature' => 74.32,
                'weather_icon_id' => '03n',
                'weather_description' => 'Clouds'
            ],
            [
                'date' => '2024-11-07 00:00:00',
                'temperature' => 75.32,
                'min_temperature' => 79.28,
                'max_temperature' => 72.32,
                'weather_icon_id' => '10n',
                'weather_description' => 'Rain'
            ],
        ];
        $this->forecastServiceMock->expects($this->once())
            ->method('fetch')
            ->with($locationDto)
            ->willReturn($responseMock);

        $matcher = $this->exactly(2);

        $this->forecastServiceMock->expects($matcher)
            ->method('store')
            ->willReturnCallback(function (ForecastDto $dto) use ($matcher, $responseMock) {
                match ($matcher->numberOfInvocations()) {
                    1 =>  $this->assertEquals(new ForecastDto(...$responseMock[0]), $dto),
                    2 =>  $this->assertEquals(new ForecastDto(...$responseMock[1]), $dto),
                };
            })
            ->willReturn(new Forecast());

        $expectedLocation = [
            'city' => $locationDto->city,
            'state' => $locationDto->state,
            'user_id' => $user->id,
        ];

        $result = $this->locationService->store($locationDto, $user);
        $resultAttributes = $result->only([
            'city',
            'state',
            'user_id',
        ]);

        $this->assertEquals($expectedLocation, $resultAttributes);
    }
}
