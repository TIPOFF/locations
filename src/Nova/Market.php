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

    public static $group = 'Escape Rooms';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            Text::make('Name')->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            Text::make('Name')->required(),
            Slug::make('Slug')->from('Name'),
            Text::make('Title'),
            Text::make('State'),
            Text::make('Timezone'),
            DateTime::make('Entered At', 'entered_at'),
            DateTime::make('Closed At', 'closed_at')->nullable(),

            nova('location') ? HasMany::make('Locations', 'locations', nova('location')) : null,

            nova('competitor') ? HasMany::make('Competitors', 'competitors', nova('competitor')) : null,

            new Panel('Content Fields', $this->contentFields()),

            new Panel('Media Fields', $this->mediaFields()),

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function contentFields()
    {
        return [
            Markdown::make('Content'),
        ];
    }

    protected function mediaFields()
    {
        return array_filter([
            nova('image') ? BelongsTo::make('Image', 'image', nova('image'))->nullable()->showCreateRelationButton() : null,
            nova('image') ? BelongsTo::make('OG Image', 'ogimage', nova('image'))->nullable()->showCreateRelationButton() : null,
            nova('image') ? BelongsTo::make('Map Image', 'map', nova('image'))->nullable()->showCreateRelationButton() : null,
            nova('video') ? BelongsTo::make('Video', 'video', nova('video'))->nullable()->showCreateRelationButton() : null,
        ]);
    }

    protected function dataFields(): array
    {
        return array_filter([
            ID::make(),
            nova('user') ? BelongsTo::make('Created By', 'creator', nova('user'))->exceptOnForms() : null,
            DateTime::make('Created At')->exceptOnForms(),
            nova('user') ? BelongsTo::make('Updated By', 'updater', nova('user'))->exceptOnForms() : null,
            DateTime::make('Updated At')->exceptOnForms(),
        ]);
    }
}
