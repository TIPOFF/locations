<?php

declare(strict_types=1);

namespace Tipoff\Locations\Services;

use Tipoff\Locations\Exceptions\UnresolvedLocation;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationResolver
{
    const TIPOFF_LOCATION = 'tipoff.location';

    public static function location(): ?Location
    {
        return app()->has(self::TIPOFF_LOCATION) ? app(self::TIPOFF_LOCATION) : null;
    }

    public function __invoke(?Market $market = null, $location = null): Location
    {
        $location = $location ?? static::location();
        if (! $location instanceof Location) {
            $market = $market ?: app(MarketResolver::class)();
            if ($market->locations()->count() !== 1) {
                throw new UnresolvedLocation($market);
            }

            $location = $market->locations->first();
        }

        app()->instance(self::TIPOFF_LOCATION, $location);

        return $location;
    }
}
