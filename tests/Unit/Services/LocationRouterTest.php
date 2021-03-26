<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Services;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationRouter;
use Tipoff\Locations\Tests\TestCase;

class LocationRouterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function build_with_single_market_single_location()
    {
        $url = \Mockery::mock(UrlGenerator::class);
        $url->shouldReceive('route')
            ->once()
            ->withArgs(function ($name, $parameters) {
                return $name === 'route';
            })
            ->andReturn('success');

        $this->app->instance('url', $url);

        $market = Market::factory()->create();
        $location = Location::factory()->create([
            'market_id' => $market,
        ]);

        $result = LocationRouter::build('route', $location);
        $this->assertEquals('success', $result);
    }

    /** @test */
    public function build_with_multiple_markets_single_location()
    {
        $url = \Mockery::mock(UrlGenerator::class);
        $url->shouldReceive('route')
            ->once()
            ->withArgs(function ($name, $parameters) {
                return $name === 'market.route' &&
                    is_array($parameters) && count($parameters) === 1 &&
                    $parameters['market'] instanceof Market;
            })
            ->andReturn('success');

        $this->app->instance('url', $url);

        $market = Market::factory()->count(2)->create()->first();
        $location = Location::factory()->create([
            'market_id' => $market,
        ]);

        $result = LocationRouter::build('route', $location);
        $this->assertEquals('success', $result);
    }

    /** @test */
    public function build_with_multiple_locations()
    {
        $url = \Mockery::mock(UrlGenerator::class);
        $url->shouldReceive('route')
            ->once()
            ->withArgs(function ($name, $parameters) {
                return $name === 'market.location.route' &&
                    is_array($parameters) && count($parameters) === 2 &&
                    $parameters['market'] instanceof Market &&
                    $parameters['location'] instanceof Location;
            })
            ->andReturn('success');

        $this->app->instance('url', $url);

        $market = Market::factory()->create();
        $locations = Location::factory()->count(2)->create([
            'market_id' => $market,
        ]);

        $result = LocationRouter::build('route', $locations->first());
        $this->assertEquals('success', $result);
    }
}
