<?php

namespace Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ForecastTest extends TestCase
{
    public function testForecastPageIsDisplayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('forecast.index'));

        $response->assertOk();
    }

    public function testUsersCanFetchForecastData(): void
    {
        $user = User::factory()->create();

        $responseMock = file_get_contents(base_path('tests/Mocks/openWeatherSuccessResponse.json'));
        Http::fake(['*' => Http::response($responseMock)]);

        $response = $this
            ->actingAs($user)
            ->post(route('forecast.fetch'), [
                'city' => 'ANDALUSIA',
                'state' => 'ALABAMA',
            ]);

        $expectedForecastMock = json_decode(
            file_get_contents(base_path('tests/Mocks/openWeatherSuccessParsedResponse.json')),
            true
        );
        $expectedForecastData = [
            $expectedForecastMock[0],
            $expectedForecastMock[1],
            $expectedForecastMock[9],
            $expectedForecastMock[17],
        ];

        $response->assertInertia(
            fn(AssertableInertia $inertia) => $inertia->component('Forecast/Index')
                ->has('forecasts', 4)
                ->where('forecasts.0', $expectedForecastData[0])
                ->where('forecasts.1', $expectedForecastData[1])
                ->where('forecasts.2', $expectedForecastData[2])
                ->where('forecasts.3', $expectedForecastData[3])
        );
    }
}
