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
            'monday_open'       => $this->faker->time('g:iA'),
            'monday_close'      => $this->faker->time('g:iA'),
            'tuesday_open'      => $this->faker->time('g:iA'),
            'tuesday_close'     => $this->faker->time('g:iA'),
            'wednesday_open'    => $this->faker->time('g:iA'),
            'wednesday_close'   => $this->faker->time('g:iA'),
            'thursday_open'     => $this->faker->time('g:iA'),
            'thursday_close'    => $this->faker->time('g:iA'),
            'friday_open'       => $this->faker->time('g:iA'),
            'friday_close'      => $this->faker->time('g:iA'),
            'saturday_open'     => $this->faker->time('g:iA'),
            'saturday_close'    => $this->faker->time('g:iA'),
            'sunday_open'       => $this->faker->time('g:iA'),
            'sunday_close'      => $this->faker->time('g:iA'),
            'location_id'       => randomOrCreate(app('location')),
            'created_at'        => Date::now()
        ];
    }

    /**
     * Pass in a day where GmbHours indicate closed.
     *
     * @param string $day
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function closed($day)
    {
        return $this->state(function (array $attributes) use ($day) {
            return [
                $day . '_open' => null,
                $day . '_close' => null,
            ];
        });
    }
}
