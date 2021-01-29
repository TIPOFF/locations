<?php

namespace Tipoff\Locations;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tipoff\Locations\Commands\LocationsCommand;

class LocationsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('locations')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_locations_table')
            ->hasCommand(LocationsCommand::class);
    }
}
