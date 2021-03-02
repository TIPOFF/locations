<?php

declare(strict_types=1);

namespace Tipoff\Locations\Services;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationRouter
{
    public static function build(string $routeName, Market $market, Location $location): string
    {
        if ($market->locations->count() !== 1) {
            return route($routeName, [
                $market,
                $location,
            ]);
        }

        return route($routeName, $market);
    }
}
