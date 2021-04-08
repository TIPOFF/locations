<?php

declare(strict_types=1);

namespace Tipoff\Locations\Tests\Unit\Models;

use DrewRoberts\Blog\Exceptions\InvalidSlugException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Market;
use Tipoff\Locations\Tests\TestCase;

class MarketTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function create_market()
    {
        $this->actingAs(User::factory()->create());
        $model = Market::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function cannot_create_repeat_slug_markets()
    {
        $this->actingAs(User::factory()->create());

        Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $this->withoutExceptionHandling();
        $this->expectException(InvalidSlugException::class);
        $this->expectExceptionMessage("Slug is not allowed.");

        Market::factory()->create([
            'slug' => 'random_text',
        ]);
    }

    /** @test */
    public function cannot_update_slug_using_existing_one_markets()
    {
        $this->actingAs(User::factory()->create());

        $model = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $model2 = Market::factory()->create([
            'slug' => 'some_text',
        ]);

        $this->expectException(InvalidSlugException::class);
        $this->expectExceptionMessage("Slug is not allowed.");

        $model2->slug = 'random_text';
        $model2->save();
    }

    /** @test */
    public function can_update_slug_with_a_no_used_one_markets()
    {
        $this->actingAs(User::factory()->create());

        $model = Market::factory()->create([
            'slug' => 'random_text',
        ]);

        $model2 = Market::factory()->create([
            'slug' => 'some_text',
        ]);

        $model2->slug = 'new_text';
        $model2->save();

        $model2->refresh();

        $this->assertEquals('new_text', $model2->slug);
    }

    /** @test */
    public function default_slug()
    {
        $market = Market::factory()->create([
            'slug' => null,
        ]);
        $this->assertEquals(Str::slug($market->city), $market->slug);
    }
}
