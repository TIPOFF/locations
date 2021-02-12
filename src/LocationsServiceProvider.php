<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Policies\LocationPolicy;
use Tipoff\Locations\Policies\MarketPolicy;

class LocationsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Location::class => LocationPolicy::class,
                Market::class => MarketPolicy::class,
            ])
            ->name('locations')
            ->hasConfigFile();
    }
}
