<?php

declare(strict_types=1);

namespace Tipoff\Locations\Traits;

use Illuminate\Database\Eloquent\Builder;
use Tipoff\Locations\Models\Location;
use Tipoff\Support\Contracts\Models\UserInterface;

trait HasLocationPermissions
{
    protected function hasLocationPermission(UserInterface $user, string $permission, ?int $locationId): bool
    {
        if ($user->hasPermissionTo($permission)) {
            if ($locationId) {
                if ($user->hasPermissionTo('all locations')) {
                    return true;
                }

                return Location::query()->where('id', '=', $locationId)
                    ->whereHas('users', function (Builder $query) use ($user) {
                        $query->where('id', '=', $user->getId());
                    })
                    ->exists();
            }

            return true;
        }

        return false;
    }
}
