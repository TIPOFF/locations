<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Policies\LocationPolicy;
use Tipoff\Locations\Policies\MarketPolicy;

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
            ->name('locations')
            ->hasConfigFile();
    }

    public function registeringPackage()
    {
        Gate::policy(Location::class, LocationPolicy::class);
        Gate::policy(Market::class, MarketPolicy::class);
    }
}
