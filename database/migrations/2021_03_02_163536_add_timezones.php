<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Tipoff\Locations\Models\Timezone;

class AddTimezones extends Migration
{
    public function up()
    {
        if (app()->has(Timezone::class)) {
            foreach([
                    [
                        'id'                 => 1,
                        'name'               => 'EST',
                        'title'              => 'Eastern',
                        'php'                => 'America/New_York',
                        'is_daylight_saving' => 1,
                        'dst'                => '-4.00',
                        'standard'           => '-5.00',
                    ],
                    [
                        'id'                 => 2,
                        'name'               => 'CST',
                        'title'              => 'Central',
                        'php'                => 'America/Chicago',
                        'is_daylight_saving' => 1,
                        'dst'                => '-5.00',
                        'standard'           => '-6.00',
                    ],
                    [
                        'id'                 => 3,
                        'name'               => 'MST',
                        'title'              => 'Mountain',
                        'php'                => 'America/Denver',
                        'is_daylight_saving' => 1,
                        'dst'                => '-6.00',
                        'standard'           => '-7.00',
                    ],
                    [
                        'id'                 => 4,
                        'name'               => 'MDT',
                        'title'              => 'Mountain no DST',
                        'php'                => 'America/Phoenix',
                        'is_daylight_saving' => 0,
                        'dst'                => '-7.00',
                        'standard'           => '-8.00',
                    ],
                    [
                        'id'                 => 5,
                        'name'               => 'PST',
                        'title'              => 'Pacific',
                        'php'                => 'America/Los_Angeles',
                        'is_daylight_saving' => 1,
                        'dst'                => '-8.00',
                        'standard'           => '-8.00',
                    ],
                    [
                        'id'                 => 6,
                        'name'               => 'AKST',
                        'title'              => 'Alaska',
                        'php'                => 'America/Anchorage',
                        'is_daylight_saving' => 1,
                        'dst'                => '-8.00',
                        'standard'           => '-9.00',
                    ],
                    [
                        'id'                 => 7,
                        'name'               => 'HAST',
                        'title'              => 'Hawaii–Aleutian',
                        'php'                => 'America/Adak',
                        'is_daylight_saving' => 1,
                        'dst'                => '-9.00',
                        'standard'           => '-10.00',
                    ],
                    [
                        'id'                 => 8,
                        'name'               => 'HADT',
                        'title'              => 'Hawaii no DST',
                        'php'                => 'Pacific/Honolulu',
                        'is_daylight_saving' => 0,
                        'dst'                => '-9.00',
                        'standard'           => '-9.00',
                    ]
                ] as $timezone) {
                Timezone::findOrCreate($timezone);
            }
        }
    }
}
