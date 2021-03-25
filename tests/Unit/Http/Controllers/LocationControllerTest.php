<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Http\Controllers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index()
    {
        $location = Location::factory()->create();
        $market = $location->market;

        $prefix = config('tipoff.web.uri_prefix');
        $this->get("{$prefix}/{$market->slug}/{$location->slug}")
            ->assertOk()
            ->assertSee($location->name)
            ->assertSee($market->name);
    }
}
