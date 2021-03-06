<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\GmbDetail;
use Tipoff\Locations\Tests\TestCase;

class GmbDetailTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = GmbDetail::factory()->create();
        $this->assertNotNull($model);
    }
}
