<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Tipoff\Locations\Models\GmbDetail;

class GmbDetailFactory extends Factory
{
    protected $model = GmbDetail::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->name,
            'opened_at'     => $this->faker->date('Y-m-d'),
            'latitude'      => $this->faker->latitude,
            'longitude'     => $this->faker->longitude,
            'location_id'   => randomOrCreate(app('location')),
            'created_at'    => Date::now()
        ];
    }
}
