<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Exceptions\UnresolvedLocation;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationResolver;
use Tipoff\Locations\Tests\TestCase;

class LocationResolverTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function resolve_with_single_locations()
    {
        $market = Market::factory()->count(2)->create()->first();
        $location = Location::factory()->create([
            'market_id' => $market,
        ]);

        $result = (new LocationResolver)($market);
        $this->assertEquals($location->id, $result->id);
    }

    /** @test */
    public function fail_with_no_locations()
    {
        $market = Market::factory()->create();

        $this->expectException(UnresolvedLocation::class);

        (new LocationResolver)($market);
    }

    /** @test */
    public function fail_with_multiple_locations()
    {
        $market = Market::factory()->create();
        Location::factory()->count(2)->create([
            'market_id' => $market,
        ]);

        $this->expectException(UnresolvedLocation::class);

        (new LocationResolver)($market);
    }
}
