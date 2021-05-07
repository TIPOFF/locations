<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Sixlive\TextCopy\TextCopy;
use Tipoff\Support\Nova\BaseResource;

class Market extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\Market::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'name';

    public function title()
    {
        return $this->name . ', ' . $this->state->abbreviation;
    }

    public static $search = [
        'id', 'name', 'title',
    ];

    public static $group = 'Locations';

    public function actions(Request $request)
    {
        return [
            (new Actions\PreviewMarket())
                ->onlyOnTableRow()
                ->withoutConfirmation(),
        ];
    }

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            nova('state') ? BelongsTo::make('State', 'state', nova('state'))->sortable() : null,
            Text::make('Name')->sortable(),
            Text::make('Title')->sortable(),
            // @todo Add location count
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('state') ? BelongsTo::make('State', 'state', nova('state'))->required() : null,
            Text::make('Name')->rules('required')->creationRules('unique:markets,name')->updateRules('unique:markets,name,{{resourceId}}'),
            Slug::make('Slug')->from('Name')->rules('required')->creationRules('unique:markets,slug')->updateRules('unique:markets,slug,{{resourceId}}'),
            TextCopy::make('Link',  function () {
                return config('app.url') . config('tipoff.web.uri_prefix') . $this->path;
            })->hideWhenCreating()->hideWhenUpdating(),
            Text::make('Title')->nullable()->creationRules('unique:markets,title')->updateRules('unique:markets,title,{{resourceId}}'),

            new Panel('Info Fields', $this->infoFields()),

            nova('market_announcement') ? HasMany::make('Market Announcements', 'announcements', nova('market_announcement')) : null,

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
            DateTime::make('Entered At', 'entered_at')->nullable(),
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
