<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\ProfileLink;

class ProfileLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProfileLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'link' => $this->faker->url,
            'type' => $this->faker->text,
            'location_id' => Location::factory()->create()->id
        ];
    }
}
