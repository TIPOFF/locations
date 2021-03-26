<?php

declare(strict_types=1);

namespace Tipoff\Locations\Exceptions;

use Exception;

class UnresolvedMarket extends Exception implements LocationException
{
    public function __construct()
    {
        parent::__construct("Could not resolve market");
    }

    public function render()
    {
        return view('locations::location_select');
    }
}
