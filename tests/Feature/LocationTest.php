<?php

namespace Feature;

use App\Models\Forecast;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    public function testLocationPageIsDisplayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('location.index'));

        $response->assertOk();
    }

    public function testUsersCanDeleteLocation(): void
    {
        $user = User::factory()
            ->has(Location::factory())
            ->create();
        $location = $user->locations()->first();

        $response = $this
            ->actingAs($user)
            ->delete(route('location.destroy', $location->id));

        $response->assertRedirect('/');

        $this->assertDatabaseMissing($location->getTable(), ['id' => $location->id]);
    }

    public function testUsersCanStoreLocation(): void
    {
        $user = User::factory()->create();

        $responseMock = file_get_contents(base_path('tests/Mocks/openWeatherSuccessResponse.json'));
        Http::fake(['*' => Http::response($responseMock)]);

        $response = $this
            ->actingAs($user)
            ->post(route('location.store'), [
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
        $expectedLocation = [
            'city' => 'ANDALUSIA',
            'state' => 'ALABAMA',
            'user_id' => $user->id,
        ];

        $response->assertRedirect('/');

        $createdLocation = Location::query()
            ->where('user_id', $user->id)
            ->first();

        $this->assertEquals($expectedLocation, $createdLocation->only(['city', 'state', 'user_id']));

        $createdLocation->forecasts->each(function (Forecast $forecast, int $key) use ($expectedForecastData) {
            $forecast = $forecast->only([
                'date',
                'temperature',
                'min_temperature',
                'max_temperature',
                'weather_icon_id',
                'weather_description',
            ]);
            $this->assertEquals($expectedForecastData[$key], $forecast);
        });
    }
}
