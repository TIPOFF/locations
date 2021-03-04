<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Locations\Models\ProfileLink;
use Tipoff\Locations\Tests\TestCase;

class ProfileLinkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $model = ProfileLink::factory()->create();
        $this->assertNotNull($model);
    }
}
