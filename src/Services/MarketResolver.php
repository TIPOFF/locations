<?php

declare(strict_types=1);

namespace Tipoff\Locations\Services;

use Tipoff\Locations\Exceptions\UnresolvedMarket;
use Tipoff\Locations\Models\Market;

class MarketResolver
{
    const TIPOFF_MARKET = 'tipoff.market';

    public static function market(): ?Market
    {
        return app()->has(self::TIPOFF_MARKET) ? app(self::TIPOFF_MARKET) : null;
    }

    public function resolve($market = null): Market
    {
        $market = $market ?? static::market();
        if (! $market instanceof Market) {
            if (Market::query()->count() !== 1) {
                throw new UnresolvedMarket();
            }

            $market = Market::query()->firstOrFail();
        }

        app()->instance(self::TIPOFF_MARKET, $market);

        return $market;
    }
}
