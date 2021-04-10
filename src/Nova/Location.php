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
            Text::make('Abbreviation')->required(),
            nova('timezone') ? BelongsTo::make('Timezone', 'timezone', nova('timezone'))->required() : null,

            new Panel('Info Fields', $this->infoFields()),
            
            new Panel('GMB Fields', $this->gmbFields()),

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
            nova('domestic_address') ? BelongsTo::make('Domestic Address', 'domestic_address', nova('domestic_address'))->nullable() : null,
            nova('phone') ? BelongsTo::make('Phone', 'phone', nova('phone'))->nullable() : null,
            Date::make('Closed At')->nullable(),
            Text::make('Title Part')->nullable(),
            nova('user') ? BelongsTo::make('Manager', 'manager', nova('user'))->nullable() : null,
            nova('email_address') ? BelongsTo::make('Email Address', 'email_address', nova('email_address'))->nullable() : null,
            Text::make('Maps URL', 'maps_url')->nullable(),
            Text::make('Review URL', 'review_url')->nullable(),
            Number::make('Reviews')->min(0)->max(99999999)->step(1)->nullable(),
            Number::make('Rating')->min(1)->max(5)->step(0.1)->nullable(),
        ];
    }
    
    protected function gmbFields()
    {
        return [
            nova('gmb_account') ? BelongsTo::make('GMB Account', 'gmb_account', nova('gmb_account'))->nullable() : null,
            Text::make('Gmb Location')->nullable(),
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
