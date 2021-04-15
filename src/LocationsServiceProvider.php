<?php

declare(strict_types=1);

namespace Tipoff\Locations;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Tipoff\Locations\Commands\SyncLocations;
use Tipoff\Locations\Http\Middleware\ResolveLocation;
use Tipoff\Locations\Models\GmbDetail;
use Tipoff\Locations\Models\GmbHour;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Policies\GmbDetailPolicy;
use Tipoff\Locations\Policies\GmbHourPolicy;
use Tipoff\Locations\Policies\LocationPolicy;
use Tipoff\Locations\Policies\MarketPolicy;
use Tipoff\Locations\ViewComposers\LocationSelectComposer;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class LocationsServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                GmbDetail::class => GmbDetailPolicy::class,
                GmbHour::class => GmbHourPolicy::class,
                Location::class => LocationPolicy::class,
                Market::class => MarketPolicy::class,
            ])
            ->hasNovaResources([
                \Tipoff\Locations\Nova\GmbDetail::class,
                \Tipoff\Locations\Nova\GmbHour::class,
                \Tipoff\Locations\Nova\Location::class,
                \Tipoff\Locations\Nova\Market::class,
            ])
            ->hasCommands([
                SyncLocations::class,
            ])
            ->name('locations')
            ->hasViews()
            ->hasConfigFile();
    }

    public function bootingPackage()
    {
        parent::bootingPackage();

        // Must happen AFTER SubstituteBindings middleware has been applied
        app(Kernel::class)->appendToMiddlewarePriority(ResolveLocation::class);

        Route::model('market', Market::class);
        Route::model('location', Location::class);

        // Make sure we dont accidentally steal any nova API routes
        Route::pattern('market', '^(?!(nova-api|nova-vendor))[^\/]+$');

        /**
         * Route macro for registering a location based route.  Note the `$routeName`, not the `$uri`,
         * is the first parameter.
         *
         * Use `app(LocationRouter::class)::build($routeName, $location)` to construct proper URLs for
         * the route.  `$location` can be omitted to use the current location in context.
         */
        Route::macro('getLocation', function (string $routeName, string $uri, $action = null) {
            Route::middleware(config('tipoff.web.middleware_group'))
                ->group(function () use ($uri, $action, $routeName) {

                    // NOTE - all 3 variations are always being registered to allow
                    // changes in location / market counts after routes have been cached!

                    Route::middleware(ResolveLocation::class)
                        ->get('company/' . $uri, $action)
                        ->name($routeName);

                    Route::middleware(ResolveLocation::class)
                        ->get('{market}/{location}/' . $uri, $action)
                        ->name('market.location.' . $routeName);

                    Route::middleware(ResolveLocation::class)
                        ->get('{market}/' . $uri, $action)
                        ->name('market.' . $routeName);
                });
        });

        Route::macro('postLocation', function (string $routeName, string $uri, $action = null) {
            Route::middleware(config('tipoff.web.middleware_group'))
                ->group(function () use ($uri, $action, $routeName) {

                    // NOTE - all 3 variations are always being registered to allow
                    // changes in location / market counts after routes have been cached!

                    Route::middleware(ResolveLocation::class)
                        ->post('company/' . $uri, $action)
                        ->name($routeName);

                    Route::middleware(ResolveLocation::class)
                        ->post('{market}/{location}/' . $uri, $action)
                        ->name('market.location.' . $routeName);

                    Route::middleware(ResolveLocation::class)
                        ->post('{market}/' . $uri, $action)
                        ->name('market.' . $routeName);
                });
        });

        View::composer('locations::location_select', LocationSelectComposer::class);
    }
}
