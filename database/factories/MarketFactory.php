<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Market;

class MarketFactory extends Factory
{
    protected $model = Market::class;

    public function definition()
    {
        $city = $this->faker->city;

        return [
            'page_id'             => randomOrCreate(app('page')),
            'name'                => $city,
            'title'               => $city,
            'state_id'            => randomOrCreate(app('state')),
            'entered_at'          => $this->faker->date(),
            'timezone_id'         => randomOrCreate(app('timezone')),
            'creator_id'          => randomOrCreate(app('user')),
            'updater_id'          => randomOrCreate(app('user')),
        ];
    }
}
