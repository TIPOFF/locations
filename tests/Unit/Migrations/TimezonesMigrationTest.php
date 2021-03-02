<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Migrations;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Timezone;
use Tipoff\Locations\Tests\TestCase;

class TimezonesMigrationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function timezones_seeded()
    {
        $this->assertTrue(Schema::hasTable('timezones'));

        $seededTimezones = Timezone::all();

        $this->assertCount(8, $seededTimezones);
    }
}
