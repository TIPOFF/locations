<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $city = $this->faker->city;

        return [
            'name'                  => $city,
            'slug'                  => Str::slug($city),
            'title_part'            => $city,
            'market_id'             => randomOrCreate(Market::class),
            'timezone_id'           => randomOrCreate(app('timezone')),
            'creator_id'            => randomOrCreate(app('user')),
            'updater_id'            => randomOrCreate(app('user'))
        ];
    }
}
