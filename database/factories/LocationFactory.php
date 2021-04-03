<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Location;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition()
    {
        $city = $this->faker->city;

        return [
            'name'                  => $city,
            'slug'                  => Str::slug($city),
            'title_part'            => $city,
            'page_id'               => randomOrCreate(app('page')),
            'market_id'             => randomOrCreate(app('market')),
            'timezone_id'           => randomOrCreate(app('timezone')),
            'domestic_address_id'   => randomOrCreate(app('domestic_address')),
            'phone_id'              => randomOrCreate(app('phone')),
            'creator_id'            => randomOrCreate(app('user')),
            'updater_id'            => randomOrCreate(app('user'))
        ];
    }
}
