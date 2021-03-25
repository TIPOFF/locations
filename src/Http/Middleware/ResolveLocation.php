<?php

declare(strict_types=1);

namespace Tipoff\Locations\Http\Middleware;

use Closure;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationResolver;
use Tipoff\Locations\Services\MarketResolver;

class ResolveLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $market = app(MarketResolver::class)->resolve($request->route('market'));
        $request->route()->setParameter('market', $market);

        $location = app(LocationResolver::class)->resolve($market, $request->route('location'));
        $request->route()->setParameter('location', $location);

        $this->ensureLocationBelongsToMarket($market, $location);

        return $next($request);
    }

    protected function ensureLocationBelongsToMarket(Market $market, ?Location $location): void
    {
        if ($location && ! $market->locations->contains($location)) {
            abort(404);
        }
    }
}
