<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\Location;
use Tipoff\Support\Contracts\Models\UserInterface;

class LocationPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view locations') ? true : false;
    }

    public function view(UserInterface $user, Location $location): bool
    {
        return $user->hasPermissionTo('view locations') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create locations') ? true : false;
    }

    public function update(UserInterface $user, Location $location): bool
    {
        return $user->hasPermissionTo('update locations') ? true : false;
    }

    public function delete(UserInterface $user, Location $location): bool
    {
        return false;
    }

    public function restore(UserInterface $user, Location $location): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Location $location): bool
    {
        return false;
    }
}
