<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Tipoff\Support\Nova\BaseResource;

class MarketAnnouncement extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\MarketAnnouncement::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'title';

    public static $search = [
        'id',
    ];

    public static $group = 'Locations';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            Text::make('Title', 'title'),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            Text::make('Title', 'title'),
            Textarea::make('Description', 'description'),
            Boolean::make('Active', 'active'),
        ]);
    }

    protected function dataFields(): array
    {
        return array_merge(
            parent::dataFields(),
            $this->creatorDataFields(),
            $this->updaterDataFields(),
        );
    }
}
