<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\GmbDetail;
use Tipoff\Locations\Models\Location;

class GmbDetailFactory extends Factory
{
    protected $model = GmbDetail::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->name,
            'opened_at'     => $this->faker->date('Y-m-d'),
            'address'       => $this->faker->address,
            'address2'      => $this->faker->address,
            'city'          => $this->faker->city,
            'state'         => $this->faker->state,
            'zip'           => $this->faker->postcode,
            'phone'         => $this->faker->phoneNumber,
            'latitude'      => $this->faker->latitude,
            'longitude'     => $this->faker->longitude,
            'location_id'   => Location::factory()->create()->id
        ];
    }
}
