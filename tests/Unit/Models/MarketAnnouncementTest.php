<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\MarketAnnouncement;
use Tipoff\Locations\Tests\TestCase;

class MarketAnnouncementTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create()
    {
        $this->actingAs(User::factory()->create());
        $model = MarketAnnouncement::factory()->create();
        $this->assertNotNull($model);
    }
}
