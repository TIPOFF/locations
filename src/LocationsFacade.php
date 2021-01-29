<?php

namespace Tipoff\Locations;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tipoff\Locations\Locations
 */
class LocationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'locations';
    }
}
