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
        if (app()->has(self::TIPOFF_MARKET)) {
            return app(self::TIPOFF_MARKET);
        }

        if ($marketId = session(self::TIPOFF_MARKET)) {
            /** @var Market $market */
            $market = Market::query()->findOrFail($marketId);
            app()->instance(self::TIPOFF_MARKET, $market);
            return $market;
        }

        return null;
    }

    public function __invoke($market = null): Market
    {
        $market = $market ?? static::market();
        if (! $market instanceof Market) {
            if (Market::query()->count() !== 1) {
                throw new UnresolvedMarket();
            }

            $market = Market::query()->firstOrFail();
        }

        app()->instance(self::TIPOFF_MARKET, $market);
        /** @psalm-suppress UndefinedMagicPropertyFetch */
        session([self::TIPOFF_MARKET => $market->id]);

        return $market;
    }
}
