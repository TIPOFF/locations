<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddLocationPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view locations' => ['Owner', 'Staff'],
            'create locations' => ['Owner'],
            'update locations' => ['Owner'],
            'view markets' => ['Owner', 'Staff'],
            'create markets' => ['Owner'],
            'update markets' => ['Owner'],
            'view gmb details' => ['Owner', 'Staff'],
            'view gmb hours' => ['Owner', 'Staff'],
        ];

        $this->createPermissions($permissions);
    }
}
