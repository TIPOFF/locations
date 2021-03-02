<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
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
}
