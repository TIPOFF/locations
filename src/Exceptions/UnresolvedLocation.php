<?php

declare(strict_types=1);

namespace Tipoff\Locations\Exceptions;

use Exception;
use Tipoff\Locations\Models\Market;

class UnresolvedLocation extends Exception implements LocationException
{
    protected $market;

    public function __construct(Market $market)
    {
        parent::__construct("Could not resolve location for { $market->name }");

        $this->market = $market;
    }

    public function render()
    {
        return view('locations::location_select');
    }
}
