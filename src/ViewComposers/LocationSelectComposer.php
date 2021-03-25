<?php

declare(strict_types=1);

namespace Tipoff\Locations\ViewComposers;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Services\MarketResolver;

class LocationSelectComposer
{
    public function compose($view)
    {
        $market = MarketResolver::market();
        $locations = $market ? $market->locations : Location::all();

        $view->with([
            'market' => $market,
            'locations' => $locations,
        ]);
    }
}
