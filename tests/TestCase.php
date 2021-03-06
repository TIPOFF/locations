<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests;

use Laravel\Nova\NovaCoreServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use DrewRoberts\Media\MediaServiceProvider;
use Tipoff\Addresses\AddressesServiceProvider;
use Tipoff\Authorization\AuthorizationServiceProvider;
use Tipoff\Locations\LocationsServiceProvider;
use Tipoff\Locations\Tests\Support\Providers\NovaPackageServiceProvider;
use Tipoff\Seo\SeoServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            AddressesServiceProvider::class,
            AuthorizationServiceProvider::class,
            PermissionServiceProvider::class,
            MediaServiceProvider::class,
            LocationsServiceProvider::class,
            SeoServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
        ];
    }
}
