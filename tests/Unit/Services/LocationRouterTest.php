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
    public function build_with_single_locations()
    {
        $url = \Mockery::mock(UrlGenerator::class);
        $url->shouldReceive('route')
            ->once()
            ->withArgs(function ($name, $parameters) {
                return $name === 'route' &&
                    $parameters instanceof Market;
            })
            ->andReturn('success');

        $this->app->instance('url', $url);

        $market = Market::factory()->create();
        $location = Location::factory()->create([
            'market_id' => $market,
        ]);

        $result = LocationRouter::build('route', $market, $location);
        $this->assertEquals('success', $result);
    }

    /** @test */
    public function build_with_multiple_locations()
    {
        $url = \Mockery::mock(UrlGenerator::class);
        $url->shouldReceive('route')
            ->once()
            ->withArgs(function ($name, $parameters) {
                return $name === 'route' && is_array($parameters) && count($parameters) === 2;
            })
            ->andReturn('success');

        $this->app->instance('url', $url);

        $market = Market::factory()->create();
        $locations = Location::factory()->count(2)->create([
            'market_id' => $market,
        ]);

        $result = LocationRouter::build('route', $market, $locations->first());
        $this->assertEquals('success', $result);
    }
}
