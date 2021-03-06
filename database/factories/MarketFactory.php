<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Market;

class MarketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Market::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $city = $this->faker->city;

        return [
            'slug'                => Str::slug($city),
            'name'                => $city,
            'title'               => $city,
            'state'               => $this->faker->stateAbbr,
            'content'             => $this->faker->sentences(3, true),
            'entered_at'          => $this->faker->date(),
            'timezone_id'         => randomOrCreate(app('timezone')),
            'creator_id'          => randomOrCreate(app('user')),
            'updater_id'          => randomOrCreate(app('user')),
        ];
    }
}
