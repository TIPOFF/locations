<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\GmbHour;
use Tipoff\Locations\Tests\TestCase;

class GmbHourTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = GmbHour::factory()->create();
        $this->assertNotNull($model);
    }
}
