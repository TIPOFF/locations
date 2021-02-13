<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Policies\Location;
use Tipoff\Locations\Policies\Market;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

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
