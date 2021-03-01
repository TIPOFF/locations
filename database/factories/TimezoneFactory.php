<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\Timezone;

class TimezoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Timezone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->timezone,
            'title' => $this->faker->timezone,
            'php' => $this->faker->timezone,
            'is_daylight_saving' => $this->faker->boolean
        ];
    }
}
