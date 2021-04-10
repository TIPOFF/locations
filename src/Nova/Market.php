<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class Market extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\Market::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'name';

    public function title()
    {
        return $this->name . ', ' . $this->state;
    }

    public static $search = [
        'id',
    ];

    public static $group = 'Locations';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            nova('state') ? BelongsTo::make('State', 'state', nova('state'))->sortable() : null,
            Text::make('Name')->sortable(),
            // @todo Add location count
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('state') ? BelongsTo::make('State', 'state', nova('state'))->required() : null,
            Text::make('Name')->required(),
            Slug::make('Slug')->from('Name'),
            Text::make('Title')->nullable(),

            new Panel('Info Fields', $this->infoFields()),

            nova('location') ? HasMany::make('Locations', 'locations', nova('location')) : null,

            nova('competitor') ? HasMany::make('Competitors', 'competitors', nova('competitor')) : null,

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function infoFields()
    {
        return [
            nova('page') ? BelongsTo::make('Page', 'page', nova('page'))->exceptOnForms() : null,
            nova('timezone') ? BelongsTo::make('Timezone', 'timezone', nova('timezone'))->nullable() : null,
            DateTime::make('Entered At', 'entered_at')->nullable()
            DateTime::make('Closed At', 'closed_at')->nullable(),
            nova('image') ? BelongsTo::make('Map Image', 'map', nova('image'))->nullable()->showCreateRelationButton() : null,
        ];
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
