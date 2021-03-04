<?php

declare(strict_types=1);

namespace Tipoff\Locations\Services;

use Tipoff\Locations\Exceptions\UnresolvedLocation;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationResolver
{
    public function resolve(Market $market): Location
    {
        if ($market->locations->count() !== 1) {
            throw new UnresolvedLocation($market);
        }

        return $market->locations->first();
    }
}
