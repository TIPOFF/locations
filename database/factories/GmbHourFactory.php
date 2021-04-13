<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;
use Tipoff\Locations\Models\GmbHour;

class GmbHourFactory extends Factory
{
    protected $model = GmbHour::class;

    public function definition()
    {
        return [
            'monday_open'       => $this->faker->time('H:i A'),
            'monday_close'      => $this->faker->time('H:i A'),
            'tuesday_open'      => $this->faker->time('H:i A'),
            'tuesday_close'     => $this->faker->time('H:i A'),
            'wednesday_open'    => $this->faker->time('H:i A'),
            'wednesday_close'   => $this->faker->time('H:i A'),
            'thursday_open'     => $this->faker->time('H:i A'),
            'thursday_close'    => $this->faker->time('H:i A'),
            'friday_open'       => $this->faker->time('H:i A'),
            'friday_close'      => $this->faker->time('H:i A'),
            'saturday_open'     => $this->faker->time('H:i A'),
            'saturday_close'    => $this->faker->time('H:i A'),
            'sunday_open'       => $this->faker->time('H:i A'),
            'sunday_close'      => $this->faker->time('H:i A'),
            'location_id'       => randomOrCreate(app('location')),
            'created_at'        => Date::now()
        ];
    }
}
