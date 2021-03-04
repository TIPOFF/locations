<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\GmbHour;
use Tipoff\Support\Contracts\Models\UserInterface;

class GmbHourPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view gmb hours') ? true : false;
    }

    public function view(UserInterface $user, GmbHour $gmb_hour): bool
    {
        return $user->hasPermissionTo('view gmb hours') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create gmb hours') ? true : false;
    }

    public function update(UserInterface $user, GmbHour $gmb_hour): bool
    {
        return $user->hasPermissionTo('update gmb hours') ? true : false;
    }

    public function delete(UserInterface $user, GmbHour $gmb_hour): bool
    {
        return false;
    }

    public function restore(UserInterface $user, GmbHour $gmb_hour): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, GmbHour $gmb_hour): bool
    {
        return false;
    }
}
