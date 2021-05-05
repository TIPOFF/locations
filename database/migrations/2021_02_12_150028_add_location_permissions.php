<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddLocationPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'all locations' => ['Owner', 'Executive'],
            'view locations' => ['Owner', 'Executive', 'Staff'],
            'create locations' => ['Owner', 'Executive'],
            'update locations' => ['Owner', 'Executive'],
            'view markets' => ['Owner', 'Executive', 'Staff'],
            'create markets' => ['Owner', 'Executive'],
            'update markets' => ['Owner', 'Executive'],
            'view gmb details' => ['Owner', 'Executive', 'Staff'],
            'view gmb hours' => ['Owner', 'Executive', 'Staff'],
            'view market announcements' => ['Owner', 'Executive', 'Staff'],
            'create market announcements' => ['Owner', 'Executive'],
            'update market announcements' => ['Owner', 'Executive'],
        ];

        $this->createPermissions($permissions);
    }
}
