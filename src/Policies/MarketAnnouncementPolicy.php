<?php

declare(strict_types=1);

namespace Tipoff\Locations\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Tipoff\Locations\Models\MarketAnnouncement;
use Tipoff\Support\Contracts\Models\UserInterface;

class MarketAnnouncementPolicy
{
    use HandlesAuthorization;

    public function viewAny(UserInterface $user): bool
    {
        return $user->hasPermissionTo('view market announcements') ? true : false;
    }

    public function view(UserInterface $user, MarketAnnouncement $market_announcement): bool
    {
        return $user->hasPermissionTo('view market announcements') ? true : false;
    }

    public function create(UserInterface $user): bool
    {
        return $user->hasPermissionTo('create market announcements') ? true : false;
    }

    public function update(UserInterface $user, MarketAnnouncement $market_announcement): bool
    {
        return $user->hasPermissionTo('update market announcements') ? true : false;
    }

    public function delete(UserInterface $user, MarketAnnouncement $market_announcement): bool
    {
        return false;
    }

    public function restore(UserInterface $user, MarketAnnouncement $market_announcement): bool
    {
        return false;
    }

    public function forceDelete(UserInterface $user, MarketAnnouncement $market_announcement): bool
    {
        return false;
    }
}
