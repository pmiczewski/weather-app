<?php

namespace Tests\Unit;

use App\Dtos\ForecastDto;
use App\Dtos\LocationDto;
use App\Integrators\OpenWeatherIntegrator;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenWeatherIntegratorTest extends TestCase
{
    private OpenWeatherIntegrator $openWeatherIntegrator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->openWeatherIntegrator = new OpenWeatherIntegrator();
    }

    public function testShouldCallApiAndReturnParsedData(): void
    {
        $responseMock = file_get_contents(base_path('tests/Mocks/openWeatherSuccessResponse.json'));
        Http::fake(['*' => Http::response($responseMock)]);

        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');

        Config::shouldReceive('get')
            ->with('services.open_weather.url')
            ->andReturn('fakeUrl');
        Config::shouldReceive('get')
            ->with('services.open_weather.key')
            ->andReturn('apiKey');


        $result = $this->openWeatherIntegrator->fetch($locationDto);

        $expectedForecastData = json_decode(
            file_get_contents(base_path('tests/Mocks/openWeatherSuccessParsedResponse.json')),
            true
        );
        $expectedForecastData = array_map(fn(array $forecast) => new ForecastDto(...$forecast), $expectedForecastData);

        $this->assertCount(40, $result);
        $this->assertEquals($expectedForecastData, $result);
    }

    public function testShouldThrowExceptionWhenHttp(): void
    {
        Http::fake(['*' => Http::response([], 500)]);

        $locationDto = new LocationDto('ANDALUSIA', 'ALABAMA');

        Config::shouldReceive('get')
            ->with('services.open_weather.url')
            ->andReturn('fakeUrl');
        Config::shouldReceive('get')
            ->with('services.open_weather.key')
            ->andReturn('apiKey');

        $this->expectException(RequestException::class);
        $this->openWeatherIntegrator->fetch($locationDto);
    }
}
