<?php

declare(strict_types=1);

use Tipoff\Authorization\Permissions\BasePermissionsMigration;

class AddLocationPermissions extends BasePermissionsMigration
{
    public function up()
    {
        $permissions = [
            'view locations',
            'create locations',
            'update locations',
            'view markets',
            'create markets',
            'update markets',
            'view gmb details',
            'view gmb hours',
        ];

        $this->createPermissions($permissions);
    }
}
