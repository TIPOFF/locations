<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\Market;
use Tipoff\Support\Contracts\Models\UserInterface;

class MarketPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view markets') ? true : false;
    }

    public function view(UserInterface $user, Market $market): bool
    {
        return $user->hasPermissionTo('view markets') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create markets') ? true : false;
    }

    public function update(UserInterface $user, Market $market): bool
    {
        return $user->hasPermissionTo('update markets') ? true : false;
    }

    public function delete(UserInterface $user, Market $market): bool
    {
        return false;
    }

    public function restore(UserInterface $user, Market $market): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Market $market): bool
    {
        return false;
    }
}
