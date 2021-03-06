<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\GmbDetail;
use Tipoff\Support\Contracts\Models\UserInterface;

class GmbDetailPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view gmb details') ? true : false;
    }

    public function view(UserInterface $user, GmbDetail $gmb_detail): bool
    {
        return $user->hasPermissionTo('view gmb details') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return false;
    }

    public function update(UserInterface $user, GmbDetail $gmb_detail): bool
    {
        return false;
    }

    public function delete(UserInterface $user, GmbDetail $gmb_detail): bool
    {
        return false;
    }

    public function restore(UserInterface $user, GmbDetail $gmb_detail): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, GmbDetail $gmb_detail): bool
    {
        return false;
    }
}
