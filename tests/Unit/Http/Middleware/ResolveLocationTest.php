<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Http\Middleware;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationRouter;
use Tipoff\Locations\Tests\TestCase;

class ResolveLocationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function single_market_single_location()
    {
        Route::getLocation('test', 'test', function () {
            return LocationRouter::build('test');
        });

        $location = Location::factory()->create();
        $market = $location->market;

        $this->get('company/test')
            ->assertOk()
            ->assertSee("http://localhost/company/test");

        $this->get("{$market->slug}/test")
            ->assertRedirect('company/test');

        $this->get("{$market->slug}/{$location->slug}/test")
            ->assertRedirect('company/test');
    }

    /** @test */
    public function multiple_markets_single_locations()
    {
        Route::getLocation('test', 'test', function () {
            return LocationRouter::build('test');
        });

        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get("company/test")
            ->assertOk()
            ->assertSee("-- SELECT:0 --");

        $this->get("{$market->slug}/test")
            ->assertOk()
            ->assertSee("http://localhost/{$market->slug}/test");

        $this->get("{$market->slug}/{$location->slug}/test")
            ->assertRedirect("{$market->slug}/test");
    }

    /** @test */
    public function multiple_markets_multiple_locations()
    {
        Route::getLocation('test', 'test', function () {
            return LocationRouter::build('test');
        });

        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->count(2)->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get("company/test")
            ->assertOk()
            ->assertSee("-- SELECT:0 --");

        $this->get("{$market->slug}/test")
            ->assertOk()
            ->assertSee("-- SELECT:{$market->id} --");

        $this->get("{$market->slug}/{$location->slug}/test")
            ->assertOk()
            ->assertSee("http://localhost/{$market->slug}/{$location->slug}/test");
    }
}
