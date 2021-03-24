<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Place;
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
            Text::make('Name')->sortable(),
            Text::make('Phone'),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('market') ? BelongsTo::make('Market', 'market', nova('market'))->required() : null,
            Text::make('Name')->required(),
            Slug::make('Slug')->from('Name'),
            Text::make('Title')->required(),
            Text::make('Abbreviation')->required(),
            Text::make('Timezone')->required(),
            nova('email_address') ? BelongsTo::make('Email Address', 'email_address', nova('email_address'))->sortable() : null,

            new Panel('Address Information', $this->addressFields()),

            nova('room') ? HasMany::make('Rooms', 'rooms', nova('room')) : null,

            new Panel('Review Data', $this->reviewFields()),

            new Panel('Hours of Operation', $this->hoursFields()),

            new Panel('Media Fields', $this->mediaFields()),

            nova('order') ? HasMany::make('Orders', 'orders', nova('order')) : null,

            nova('snapshot') ? HasMany::make('Snapshots', 'snapshots', nova('snapshot')) : null,

            nova('review') ? HasMany::make('Reviews', 'reviews', nova('review')) : null,

            nova('insight') ? HasMany::make('Insights', 'insights', nova('insight')) : null,

            nova('user') ? BelongsTo::make('Manager', 'manager', nova('user'))->searchable()->withSubtitles()->withoutTrashed() : null,

            new Panel('Data Fields', $this->dataFields()),
        ]);
    }

    protected function addressFields()
    {
        return [
            Place::make('Address', 'address')->nullable(),
            Text::make('Address Line 2', 'address2')->nullable(),
            Text::make('City')->nullable(),
            Text::make('State')->nullable(),
            Text::make('ZIP')->nullable(),
            Text::make('Phone')->nullable(),
        ];
    }

    protected function reviewFields()
    {
        return [
            Text::make('Reviews', 'gmb_reviews')->nullable(),
            Text::make('Rating', 'gmb_rating')->nullable(),
            Text::make('Map Link', function () {
                return '<a href="' . $this->maps_url . '">' . $this->maps_url . '</a>';
            })->asHtml()->nullable(),
            Text::make('Review Link', function () {
                return '<a href="' . $this->review_url . '">' . $this->review_url . '</a>';
            })->asHtml()->nullable(),
            Text::make('Latitude')->nullable(),
            Text::make('Longitude')->nullable(),
            Text::make('GMB ID', 'gmb_location')->required(),
            Text::make('GMB Account')->nullable(),
            Text::make('Place ID', 'place_location')->nullable(),
            Text::make('Facebook')->nullable(),
            Text::make('Tripadvisor')->nullable(),
            Text::make('Yelp')->nullable(),
        ];
    }

    protected function hoursFields()
    {
        return [
            Text::make('Monday Open')->nullable(),
            Text::make('Monday Close')->nullable(),
            Text::make('Tuesday Open')->nullable(),
            Text::make('Tuesday Close')->nullable(),
            Text::make('Wednesday Open')->nullable(),
            Text::make('Wednesday Close')->nullable(),
            Text::make('Thursday Open')->nullable(),
            Text::make('Thursday Close')->nullable(),
            Text::make('Friday Open')->nullable(),
            Text::make('Friday Close')->nullable(),
            Text::make('Saturday Open')->nullable(),
            Text::make('Saturday Close')->nullable(),
            Text::make('Sunday Open')->nullable(),
            Text::make('Sunday Close')->nullable(),
        ];
    }

    protected function bookingFields()
    {
        return [
            Date::make('Opened At')->required(),
            Date::make('Closed At')->required(),
        ];
    }

    protected function mediaFields()
    {
        return array_filter([
            nova('image') ? BelongsTo::make('Image', 'image', nova('image'))->nullable()->showCreateRelationButton() : null,
            nova('image') ? BelongsTo::make('OG Image', 'ogimage', nova('image'))->nullable()->showCreateRelationButton() : null,
            nova('video') ? BelongsTo::make('Video', 'video', nova('video'))->nullable()->showCreateRelationButton() : null,
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
