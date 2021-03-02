<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Tipoff\Locations\Commands\SyncLocations;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Models\Timezone;
use Tipoff\Locations\Policies\LocationPolicy;
use Tipoff\Locations\Policies\MarketPolicy;
use Tipoff\Locations\Policies\TimezonePolicy;
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
                Timezone::class => TimezonePolicy::class
            ])
            ->hasNovaResources([
                \Tipoff\Locations\Nova\Location::class,
                \Tipoff\Locations\Nova\Market::class,
            ])
            ->hasCommands([
                SyncLocations::class,
            ])
            ->name('locations')
            ->hasConfigFile();
    }
}
