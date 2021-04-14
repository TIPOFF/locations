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
            'monday_open'       => $this->faker->time('g:i').' AM',
            'monday_close'      => $this->faker->time('g:i').' PM',
            'tuesday_open'      => $this->faker->time('g:i').' AM',
            'tuesday_close'     => $this->faker->time('g:i').' PM',
            'wednesday_open'    => $this->faker->time('g:i').' AM',
            'wednesday_close'   => $this->faker->time('g:i').' PM',
            'thursday_open'     => $this->faker->time('g:i').' AM',
            'thursday_close'    => $this->faker->time('g:i').' PM',
            'friday_open'       => $this->faker->time('g:i').' AM',
            'friday_close'      => $this->faker->time('g:i').' PM',
            'saturday_open'     => $this->faker->time('g:i').' AM',
            'saturday_close'    => $this->faker->time('g:i').' PM',
            'sunday_open'       => $this->faker->time('g:i').' AM',
            'sunday_close'      => $this->faker->time('g:i').' PM',
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
