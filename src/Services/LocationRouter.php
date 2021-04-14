<?php

declare(strict_types=1);

namespace Tipoff\Locations\Services;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationRouter
{
    public static function build(string $routeName, ?Location $location = null, ?bool $absolute = true): string
    {
        /** @var Location $location */
        $location = $location ?: app(LocationResolver::TIPOFF_LOCATION);
        $market = $location->market;
        if ($market->locations()->count() !== 1) {
            return route('market.location.'.$routeName, [
                'market' => $market,
                'location' => $location,
            ], $absolute);
        }

        if (Market::query()->count() !== 1) {
            return route('market.'.$routeName, [
                'market' => $market,
            ], $absolute);
        }

        return route($routeName, [], $absolute);
    }
}
