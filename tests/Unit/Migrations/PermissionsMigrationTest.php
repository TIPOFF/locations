<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Migrations;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Tipoff\Locations\Tests\TestCase;

class PermissionsMigrationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function permissions_seeded()
    {
        $this->assertTrue(Schema::hasTable('permissions'));

        $seededPermissions = app(Permission::class)->whereIn('name', [
            'view locations',
            'create locations',
            'update locations',
            'view markets',
            'create markets',
            'update markets',
            'view profile links',
            'create profile links',
            'update profile links',
        ])->pluck('name');

        $this->assertCount(9, $seededPermissions);
    }
}
