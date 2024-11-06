<?php

namespace Tests\Unit;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Interfaces\WeatherForecastApiInterface;
use App\Models\Location;
use App\Models\User;
use App\Services\ForecastService;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class ForecastServiceTest extends TestCase
{
    private ForecastService $forecastService;
    private WeatherForecastApiInterface $forecastApiMock;
    protected function setUp(): void
    {
        parent::setUp();

        $this->forecastApiMock = $this->createMock(WeatherForecastApiInterface::class);
        $this->forecastService = new ForecastService($this->forecastApiMock);
    }

    public function testFetchShouldGetDataFromCacheHit(): void
    {
        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');

        $cacheKey = "$locationDto->city,$locationDto->state";
        $cachedData = ['cachedData'];

        Cache::shouldReceive('has')->with($cacheKey)->andReturn(true);
        Cache::shouldReceive('get')->with($cacheKey)->andReturn($cachedData);

        $result = $this->forecastService->fetch($locationDto);

        $this->assertEquals($cachedData, $result);
    }

    public function testFetchShouldCallApiWhenCacheMissAndCacheData(): void
    {
        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');
        $cacheKey = "$locationDto->city,$locationDto->state";

        $forecastDataMock = [
            'date' => '2024-11-06 00:00:00',
            'temperature' => 74.32,
            'min_temperature' => 71.28,
            'max_temperature' => 74.32,
            'weather_icon_id' => '03n',
            'weather_description' => 'Clouds'
        ];
        $responseMock = [
            new ForecastDto(...$forecastDataMock),
        ];

        $this->forecastApiMock->expects($this->once())
            ->method('fetch')
            ->with($locationDto)
            ->willReturn($responseMock);

        $expectedForecastData = array($forecastDataMock);
        Cache::shouldReceive('has')->with($cacheKey)->andReturn(false);
        Cache::shouldReceive('put')->with($cacheKey, $expectedForecastData, Mockery::any());

        $result = $this->forecastService->fetch($locationDto);

        $this->assertEquals($expectedForecastData, $result);
    }

    public function testFetchShouldFilterTodayMostRecentAndNextThreeDaysForecast(): void
    {
        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');

        $responseMock =  json_decode(
            file_get_contents(base_path('tests/Mocks/openWeatherSuccessParsedResponse.json')),
            true
        );
        $responseMock = array_map(fn(array $forecast) => new ForecastDto(...$forecast), $responseMock);

        $this->forecastApiMock->expects($this->once())
            ->method('fetch')
            ->with($locationDto)
            ->willReturn($responseMock);

        $expectedForecastData = [
            $responseMock[0]->toArray(),
            $responseMock[1]->toArray(),
            $responseMock[9]->toArray(),
            $responseMock[17]->toArray(),
        ];

        Cache::shouldReceive('has')->andReturn(false);
        Cache::shouldReceive('put');

        $result = $this->forecastService->fetch($locationDto);

        $this->assertCount(4, $result);
        $this->assertEquals($expectedForecastData, $result);
    }

    public function testStoreShouldStoreInDatabase(): void
    {
        $user = User::factory()
            ->has(Location::factory())
            ->create();

        $forecastDataMock = [
            'date' => '2024-11-06 00:00:00',
            'temperature' => 74.32,
            'min_temperature' => 71.28,
            'max_temperature' => 74.32,
            'weather_icon_id' => '03n',
            'weather_description' => 'Clouds'
        ];
        $forecastDto = new ForecastDto(...$forecastDataMock);
        $locationId = $user->locations()->first()->id;

        $result = $this->forecastService->store($forecastDto, $locationId);
        $result = $result->only([
            'date',
            'temperature',
            'min_temperature',
            'max_temperature',
            'weather_icon_id',
            'weather_description',
            'location_id'
        ]);

        $forecastDataMock['location_id'] = $locationId;
        $this->assertEquals($forecastDataMock, $result);
    }
}
