<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\Timezone;
use Tipoff\Locations\Tests\TestCase;

class TimezoneModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = Timezone::factory()->create();
        $this->assertNotNull($model);
    }
}
