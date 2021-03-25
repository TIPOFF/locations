<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\ViewComposers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\View\View;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class LocationSelectComposerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function no_market_context()
    {
        Location::factory()->count(4)->create();

        /** @var View $view */
        $view = view('locations::location_select');
        $view->render(function (View $view) {
            $data = $view->getData();
            $this->assertNull($data['market']);
            $this->assertCount(4, $data['locations']);
        });
    }

    /** @test */
    public function with_market_context()
    {
        $markets = Market::factory()->count(2)->create()->each(function (Market $market) {
            Location::factory()->count(2)->create([
                'market_id' => $market
            ]);
        });

        $market = $markets->first();
        $this->app->instance('tipoff.market', $market);

        /** @var View $view */
        $view = view('locations::location_select');
        $view->render(function (View $view) use ($market) {
            $data = $view->getData();
            $this->assertEquals($market->id, $data['market']->id);
            $this->assertCount(2, $data['locations']);
        });
    }
}
