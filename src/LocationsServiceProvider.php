<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationsServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        parent::boot();
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->hasModelInterfaces([
                LocationInterface::class => Location::class,
            ])
            ->hasModelInterfaces([
                MarketInterface::class => Market::class,
            ])
            ->name('locations')
            ->hasConfigFile();
    }
}
