<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Tipoff\Locations\Commands\SyncLocations;
use Tipoff\Locations\Models\GmbDetail;
use Tipoff\Locations\Models\GmbHour;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Models\ProfileLink;
use Tipoff\Locations\Policies\GmbDetailPolicy;
use Tipoff\Locations\Policies\GmbHourPolicy;
use Tipoff\Locations\Policies\LocationPolicy;
use Tipoff\Locations\Policies\MarketPolicy;
use Tipoff\Locations\Policies\ProfileLinkPolicy;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class LocationsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                GmbDetail::class    => GmbDetailPolicy::class,
                GmbHour::class      => GmbHourPolicy::class,
                Location::class     => LocationPolicy::class,
                Market::class       => MarketPolicy::class,
                ProfileLink::class  => ProfileLinkPolicy::class,
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
