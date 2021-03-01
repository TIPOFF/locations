<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\Timezone;
use Tipoff\Support\Contracts\Models\UserInterface;

class TimezonePolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view timezones') ? true : false;
    }

    public function view(UserInterface $user, Timezone $timezone): bool
    {
        return $user->hasPermissionTo('view timezones') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create timezones') ? true : false;
    }

    public function update(UserInterface $user, Timezone $timezone): bool
    {
        return $user->hasPermissionTo('update timezones') ? true : false;
    }

    public function delete(UserInterface $user, Timezone $timezone): bool
    {
        return false;
    }

    public function restore(UserInterface $user, Timezone $timezone): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, Timezone $timezone): bool
    {
        return false;
    }
}
