<?php

declare(strict_types=1);

namespace Tipoff\Locations\Http\Controllers;

use Illuminate\Http\Request;
use Tipoff\Locations\Models\Market;
use Tipoff\Support\Http\Controllers\BaseController;

class MarketController extends BaseController
{
    public function __invoke(Request $request, Market $market)
    {
        return view('locations::market', [
            'market' => $market,
        ]);
    }
}
