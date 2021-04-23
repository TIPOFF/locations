<?php

declare(strict_types=1);

namespace Tipoff\Locations\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tipoff\Locations\Models\MarketAnnouncement;

class MarketAnnouncementFactory extends Factory
{
    protected $model = MarketAnnouncement::class;

    public function definition()
    {
        return [
            'market_id'             => randomOrCreate(app('market')),
            'title'                 => $this->faker->title,
            'description'           => $this->faker->sentence,
            'creator_id'            => randomOrCreate(app('user')),
            'updater_id'            => randomOrCreate(app('user'))
        ];
    }
}
