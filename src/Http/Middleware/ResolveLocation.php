<?php

declare(strict_types=1);

namespace Tipoff\Locations\Http\Middleware;

use Closure;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Services\LocationResolver;
use Tipoff\Locations\Services\LocationRouter;
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
        $market = app(MarketResolver::class)($request->route('market'));
        $request->route()->setParameter('market', $market);

        $location = app(LocationResolver::class)($market, $request->route('location'));
        $request->route()->setParameter('location', $location);

        $this->ensureLocationBelongsToMarket($market, $location);

        $currentPath = $request->path();
        $desiredPath = LocationRouter::build(basename($currentPath), $location, false);
        if ($currentPath !== trim($desiredPath, '/')) {
            return redirect(url($desiredPath));
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
