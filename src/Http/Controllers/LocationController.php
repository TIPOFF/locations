<?php

declare(strict_types=1);

namespace Tipoff\Locations\Http\Controllers;

use Illuminate\Http\Request;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Support\Http\Controllers\BaseController;

class LocationController extends BaseController
{
    public function __invoke(Request $request, Market $market, Location $location)
    {
        return view('locations::location', [
            'market' => $market,
            'location' => $location,
        ]);
    }
}
