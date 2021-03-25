<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class MarketTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Market::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function default_slug()
    {
        $market = Market::factory()->create([
            'slug' => null,
        ]);
        $this->assertEquals(Str::slug($market->city), $market->slug);
    }

    /** @test */
    public function restricted_slug()
    {
        $market = Market::factory()->create([
            'slug' => 'company',
        ]);
        $this->assertEquals(Str::slug("company-{$market->state}"), $market->slug);
    }
}
