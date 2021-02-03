<?php namespace Tipoff\Locations\Database\Factories;

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
            'timezone'              => $this->faker->timezone,
            'market_id'             => randomOrCreate(Market::class),
            'corporate'             => $this->faker->boolean,
            'booking_tax_id'        => randomOrCreate(config('locations.model_class.tax')),
            'product_tax_id'        => randomOrCreate(config('locations.model_class.tax')),
            'creator_id'            => randomOrCreate(config('locations.model_class.user')),
            'updater_id'            => randomOrCreate(config('locations.model_class.user')),
            'stripe_secret'         => rand(100000, 900000),
        ];
    }
}
