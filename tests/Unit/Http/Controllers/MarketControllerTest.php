<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class MarketControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index_single_market_single_location()
    {
        $location = Location::factory()->create();
        $market = $location->market;

        $prefix = config('tipoff.web.uri_prefix');
        $this->get("{$prefix}/{$market->slug}")
            ->assertRedirect('/');
    }

    /** @test */
    public function index_single_market_multiple_locations()
    {
        $market = Market::factory()->create();

        $location = Location::factory()->count(2)->create([
            'market_id' => $market,
        ])->first();

        $prefix = config('tipoff.web.uri_prefix');
        $this->get("{$prefix}/{$market->slug}")
            ->assertOk()
            ->assertDontSee($location->name)
            ->assertSee($market->name);
    }

    /** @test */
    public function index_multiple_markets_single_locations()
    {
        $location = Location::factory()->count(3)->create([
            'market_id' => function () {
                return Market::factory()->create();
            },
        ])->first();
        $market = $location->market;

        $prefix = config('tipoff.web.uri_prefix');
        $this->get("{$prefix}/{$market->slug}")
            ->assertOk()
            ->assertDontSee($location->name)
            ->assertSee($market->name);
    }
}
