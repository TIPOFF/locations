<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Support\Providers;

use Tipoff\Locations\Nova\Location;
use Tipoff\Locations\Nova\Market;
use Tipoff\TestSupport\Providers\BaseNovaPackageServiceProvider;

class NovaPackageServiceProvider extends BaseNovaPackageServiceProvider
{
    public static array $packageResources = [
        Location::class,
        Market::class,
    ];
}