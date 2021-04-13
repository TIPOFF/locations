<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class Location extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\Location::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'name';

    public static $search = [
        'id',
    ];

    public static $group = 'Locations';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
            nova('market') ? BelongsTo::make('Market', 'market', nova('market'))->sortable() : null,
            Text::make('Name')->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('market') ? BelongsTo::make('Market', 'market', nova('market'))->required() : null,
            Text::make('Name')->required(),
            Slug::make('Slug')->from('Name'),
            Text::make('Abbreviation')
                ->withMeta(['extraAttributes' => ['maxlength' => 4]])
                ->required(),

            new Panel('Info Fields', $this->infoFields()),

            new Panel('GMB Fields', $this->gmbFields()),

            new Panel('Hours', $this->hoursFields()),

            nova('room') ? HasMany::make('Rooms', 'rooms', nova('room')) : null,

            nova('order') ? HasMany::make('Orders', 'orders', nova('order')) : null,

            nova('snapshot') ? HasMany::make('Snapshots', 'snapshots', nova('snapshot')) : null,

            nova('review') ? HasMany::make('Reviews', 'reviews', nova('review')) : null,

            nova('insight') ? HasMany::make('Insights', 'insights', nova('insight')) : null,

            nova('user') ? BelongsTo::make('Manager', 'manager', nova('user'))->searchable()->withSubtitles()->withoutTrashed() : null,

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function infoFields()
    {
        return [
            nova('page') ? BelongsTo::make('Page', 'page', nova('page'))->exceptOnForms() : null,
            nova('timezone') ? BelongsTo::make('Timezone', 'timezone', nova('timezone'))
                ->help('This value will be populated by the corresponding Market if left empty.')
                ->nullable() : null,
            nova('domestic_address') ? BelongsTo::make('Domestic Address', 'address', nova('domestic_address'))->nullable() : null,
            nova('phone') ? BelongsTo::make('Phone', 'phone', nova('phone'))->nullable() : null,
            Date::make('Closed At')->nullable(),
            Text::make('Title Part')->nullable(),
            nova('user') ? BelongsTo::make('Manager', 'manager', nova('user'))->nullable() : null,
            nova('email_address') ? BelongsTo::make('Email Address', 'email', nova('email_address'))->nullable() : null,
            Text::make('Maps URL', 'maps_url')->nullable(),
            Text::make('Review URL', 'review_url')->nullable(),
            Number::make('Aggregate Reviews')->min(0)->max(99999999)->step(1)->nullable(),
            Number::make('Aggregate Rating')->min(1)->max(5)->step(0.1)->nullable(),
        ];
    }

    protected function gmbFields()
    {
        return [
            nova('gmb_account') ? BelongsTo::make('GMB Account', 'gmb_account', nova('gmb_account'))->nullable() : null,
            Text::make('Gmb Location')->nullable(),
        ];
    }

    protected function hoursFields()
    {
        return [
            Text::make('Monday Open', 'gmb_hour.monday_open')->nullable(),
            Text::make('Monday Close', 'gmb_hour.monday_close')->nullable(),
            Text::make('Tuesday Open', 'gmb_hour.tuesday_open')->nullable(),
            Text::make('Tuesday Close', 'gmb_hour.tuesday_close')->nullable(),
            Text::make('Wednesday Open', 'gmb_hour.wednesday_open')->nullable(),
            Text::make('Wednesday Close', 'gmb_hour.wednesday_close')->nullable(),
            Text::make('Thursday Open', 'gmb_hour.thursday_open')->nullable(),
            Text::make('Thursday Close', 'gmb_hour.thursday_close')->nullable(),
            Text::make('Friday Open', 'gmb_hour.friday_open')->nullable(),
            Text::make('Friday Close', 'gmb_hour.friday_close')->nullable(),
            Text::make('Saturday Open', 'gmb_hour.saturday_open')->nullable(),
            Text::make('Saturday Close', 'gmb_hour.saturday_close')->nullable(),
            Text::make('Sunday Open', 'gmb_hour.sunday_open')->nullable(),
            Text::make('Sunday Close', 'gmb_hour.sunday_close')->nullable(),
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
