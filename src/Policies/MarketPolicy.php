<?php

namespace Tipoff\Locations\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\Market;

class MarketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view markets') ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function view(User $user, Market $market)
    {
        return $user->hasPermissionTo('view markets') ? true : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create markets') ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function update(User $user, Market $market)
    {
        return $user->hasPermissionTo('update markets') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function delete(User $user, Market $market)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function restore(User $user, Market $market)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Market  $market
     * @return mixed
     */
    public function forceDelete(User $user, Market $market)
    {
        return false;
    }
}
