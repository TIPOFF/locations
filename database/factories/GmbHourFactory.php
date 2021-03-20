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
            'monday_open'       => $this->faker->text,
            'monday_close'      => $this->faker->text,
            'tuesday_open'      => $this->faker->text,
            'tuesday_close'     => $this->faker->text,
            'wednesday_open'    => $this->faker->text,
            'wednesday_close'   => $this->faker->text,
            'thursday_open'     => $this->faker->text,
            'thursday_close'    => $this->faker->text,
            'friday_open'       => $this->faker->text,
            'friday_close'      => $this->faker->text,
            'saturday_open'     => $this->faker->text,
            'saturday_close'    => $this->faker->text,
            'sunday_open'       => $this->faker->text,
            'sunday_close'      => $this->faker->text,
            'location_id'       => randomOrCreate(app('location')),
            'created_at'        => Date::now()
        ];
    }
}
