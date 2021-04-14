<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class PageControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function single_market_single_location()
    {
        $this->actingAs(User::factory()->create());

        $location = Location::factory()->create();
        $market = $location->market;

        $this->get($this->webUrl("{$market->slug}"))
            ->assertRedirect('/');

        $this->get($this->webUrl("{$market->slug}/{$location->slug}"))
            ->assertRedirect('/');
    }

    /** @test */
    public function multiple_markets_single_locations()
    {
        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get($this->webUrl("{$market->slug}"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} --");

        $this->get($this->webUrl("{$market->slug}/{$location->slug}"))
            ->assertRedirect("{$market->slug}");
    }

    /** @test */
    public function multiple_markets_multiple_locations()
    {
        Market::factory()->count(2)->create()
            ->each(function (Market $market) {
                Location::factory()->count(2)->create([
                    'market_id' => $market,
                ]);
            });

        $location = Location::query()->first();
        $market = $location->market;

        $this->get($this->webUrl("{$market->slug}"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} --");

        $this->get($this->webUrl("{$market->slug}/{$location->slug}"))
            ->assertOk()
            ->assertSee("-- M:{$market->id} L:{$location->id} --");
    }
}
