<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Exceptions\UnresolvedMarket;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\MarketResolver;
use Tipoff\Locations\Tests\TestCase;

class MarketResolverTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function resolve_with_single_market()
    {
        $market = Market::factory()->create();

        $result = (new MarketResolver())->resolve();
        $this->assertEquals($market->id, $result->id);
    }

    /** @test */
    public function fail_with_no_markets()
    {
        $this->expectException(UnresolvedMarket::class);

        (new MarketResolver)->resolve();
    }

    /** @test */
    public function fail_with_multiple_markets()
    {
        $market = Market::factory()->count(2)->create();

        $this->expectException(UnresolvedMarket::class);

        (new MarketResolver)->resolve();
    }
}
