<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Tests\TestCase;
use Tipoff\Support\Contracts\Checkout\Filters\ItemFilter;
use Tipoff\Support\Contracts\Checkout\OrderInterface;
use Tipoff\Support\Contracts\Checkout\OrderItemInterface;
use Tipoff\Support\Objects\DiscountableValue;

class LocationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Location::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function bookings_yesterday_no_service()
    {
        $location = Location::factory()->create();
        $this->assertEquals(0, $location->bookings_yesterday);
    }

    /** @test */
    public function revenue_booked_yesterday_no_service()
    {
        $location = Location::factory()->create();
        $this->assertEquals(0, $location->revenue_booked_yesterday);
    }

    /** @test */
    public function bookings_last_week_no_service()
    {
        $location = Location::factory()->create();
        $this->assertEquals(0, $location->bookings_last_week);
    }

    /** @test */
    public function revenue_booked_last_week_no_service()
    {
        $location = Location::factory()->create();
        $this->assertEquals(0, $location->revenue_booked_last_week);
    }

    /** @test */
    public function bookings_yesterday_with_service()
    {
        $this->stubOrderInterfaceFiltering();

        $location = Location::factory()->create();
        $this->assertEquals(2, $location->bookings_yesterday);
    }

    /** @test */
    public function revenue_booked_yesterday_with_service()
    {
        $this->stubOrderInterfaceFiltering();

        $location = Location::factory()->create();
        $this->assertEquals(35.79, $location->revenue_booked_yesterday);
    }

    /** @test */
    public function bookings_last_week_with_service()
    {
        $this->stubOrderInterfaceFiltering();

        $location = Location::factory()->create();
        $this->assertEquals(2, $location->bookings_last_week);
    }

    /** @test */
    public function revenue_booked_last_week_with_service()
    {
        $this->stubOrderInterfaceFiltering();

        $location = Location::factory()->create();
        $this->assertEquals(35.79, $location->revenue_booked_last_week);
    }

    private function stubOrderInterfaceFiltering()
    {
        $item1 = \Mockery::mock(OrderItemInterface::class);
        $item1->shouldReceive('getAmountTotal')
            ->andReturn(new DiscountableValue(1234));

        $item2 = \Mockery::mock(OrderItemInterface::class);
        $item2->shouldReceive('getAmountTotal')
            ->andReturn(new DiscountableValue(2345));

        $itemFilter = \Mockery::mock(ItemFilter::class);
        $itemFilter->shouldReceive('bySellableType', 'byLocation', 'week', 'yesterday')
            ->andReturnSelf();
        $itemFilter->shouldReceive('apply')
            ->once()
            ->andReturn(new Collection([$item1, $item2]));

        $service = \Mockery::mock(OrderInterface::class);
        $service->shouldReceive('itemFilter')
            ->once()
            ->andReturn($itemFilter);

        $this->app->instance(OrderInterface::class, $service);
    }
}

