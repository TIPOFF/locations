<?php

declare(strict_types=1);

namespace Tipoff\Locations\Http\Middleware;

use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationResolver;
use Closure;

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
        $market = $request->route()->parameter('market');
        $location = $request->route()->parameter('location');

        $this->ensureLocationBelongsToMarket($market, $location);

        if (! $location) {
            $location = (new LocationResolver)->resolve($market);

            $request->route()->setParameter('location', $location);
        }

        return $next($request);
    }

    protected function ensureLocationBelongsToMarket(Market $market, ?Location $location): void
    {
        if ($location && ! $market->locations->contains($location)) {
            abort(404);
        }
    }
}
