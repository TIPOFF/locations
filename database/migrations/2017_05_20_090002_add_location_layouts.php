<?php

declare(strict_types=1);

use DrewRoberts\Blog\Models\Layout;
use Illuminate\Database\Migrations\Migration;
use Tipoff\Support\Enums\LayoutType;

class AddLocationLayouts extends Migration
{
    public function up()
    {
        foreach ([
            [
                'name'          => 'Base Markets Page',
                'layout_type'   => LayoutType::PAGE,
                'view'          => 'locations::page.market.base',
                'note'          => 'Base Markets HTML Structure',
            ],
            [
                'name'          => 'AMP Markets Page',
                'layout_type'   => LayoutType::PAGE,
                'view'          => 'locations::page.market.amp',
                'note'          => 'Markets AMP Structure',
            ],
             [
                 'name'          => 'Base Locations Page',
                 'layout_type'   => LayoutType::PAGE,
                 'view'          => 'locations::page.location.base',
                 'note'          => 'Base Locations HTML Structure',
             ],
             [
                 'name'          => 'AMP Locations Page',
                 'layout_type'   => LayoutType::PAGE,
                 'view'          => 'locations::page.location.amp',
                 'note'          => 'Locations AMP Structure',
             ],
        ] as $layout) {
            Layout::firstOrCreate($layout);
        }
    }
}
