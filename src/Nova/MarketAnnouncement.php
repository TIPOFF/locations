<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
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
        'id', 'title',
    ];

    public static $group = 'Locations';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            Text::make('Title', 'title')->sortable(),
            nova('market') ? BelongsTo::make('Market', 'market', nova('market'))->sortable() : null,
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            Text::make('Title', 'title')->rules('required'),
            Textarea::make('Description', 'description')->rules('required'),
            nova('market') ? BelongsTo::make('Market', 'market', nova('market'))->sortable()->rules('required') : null,
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
