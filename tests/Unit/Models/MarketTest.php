<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class MarketTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create_market()
    {
        $model = Market::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function cannot_create_repeat_slug_markets()
    {
        $model = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $model2 = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $this->assertEquals(1, Market::count());
    }

    /** @test */
    public function cannot_update_slug_using_existing_one_markets()
    {
        $model = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $model2 = Market::factory()->create([
            'slug' => 'some_text',
        ]);

        $model2->slug = 'random_text';
        $model2->save();

        $model2->refresh();

        $this->assertEquals('some_text', $model2->slug);
    }

    /** @test */
    public function can_update_slug_with_a_no_used_one_markets()
    {
        $model = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $model2 = Market::factory()->create([
            'slug' => 'some_text',
        ]);

        $model2->slug = 'new_text';
        $model2->save();
        
        $model2->refresh();

        $this->assertEquals('some_text', $model2->slug);
    }

    /** @test */
    public function default_slug()
    {
        $market = Market::factory()->create([
            'slug' => null,
        ]);
        $this->assertEquals(Str::slug($market->city), $market->slug);
    }

    /** @test */
    public function restricted_slug()
    {
        $market = Market::factory()->create([
            'slug' => 'company',
        ]);
        $this->assertEquals(Str::slug("company-{$market->state}"), $market->slug);
    }
}
