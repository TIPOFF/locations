<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Traits;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Tests\TestCase;
use Tipoff\Locations\Traits\HasLocationPermissions;
use Tipoff\Support\Contracts\Models\UserInterface;

class HasLocationPermissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_no_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view locations');

        $test = new TestClass();

        $result = $test->testIt($user, 'create locations', 123);
        $this->assertFalse($result);
    }

    /** @test */
    public function test_permission_no_user_location()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view locations');

        $test = new TestClass();

        $result = $test->testIt($user, 'view locations', 123);
        $this->assertFalse($result);
    }

    /** @test */
    public function test_permission_with_user_location()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view locations');

        /** @var Location $location */
        $location = Location::factory()->create();
        $location->users()->attach($user);

        $test = new TestClass();

        $result = $test->testIt($user, 'view locations', $location->id);
        $this->assertTrue($result);
    }

    /** @test */
    public function test_permission_with_no_target_location()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view locations');

        $test = new TestClass();

        $result = $test->testIt($user, 'view locations', null);
        $this->assertTrue($result);
    }

    /** @test */
    public function test_permission_with_all_locations()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('view locations');
        $user->givePermissionTo('all locations');

        $test = new TestClass();

        $result = $test->testIt($user, 'view locations', 123);
        $this->assertTrue($result);
    }
}

class TestClass
{
    use HasLocationPermissions;

    public function testIt(UserInterface $user, string $permission, ?int $locationId): bool
    {
        return $this->hasLocationPermission($user, $permission, $locationId);
    }
}
