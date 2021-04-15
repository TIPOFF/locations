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
        if (app()->has(self::TIPOFF_LOCATION)) {
            return app(self::TIPOFF_LOCATION);
        }

        if ($locationId = session(self::TIPOFF_LOCATION)) {
            /** @var Location $location */
            $location = Location::query()->findOrFail($locationId);
            app()->instance(self::TIPOFF_LOCATION, $location);

            return $location;
        }

        return null;
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
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        session([self::TIPOFF_LOCATION => $location->id]);

        return $location;
    }
}
