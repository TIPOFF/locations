<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class GmbDetail extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\GmbDetail::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'name';

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
            nova('gmb_detail') ? BelongsTo::make('GmbDetail', 'gmb_detail', nova('gmb_detail'))->required() : null,
            Text::make('Name')->required(),
            Slug::make('Slug')->from('Name'),
            Text::make('Title')->required(),

            new Panel('Address Information', $this->addressFields()),

            nova('webpage') ? HasMany::make('Webpage', 'webpage', nova('webpage')) : null,

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
            Text::make('Latitude'),
            Text::make('Longitude'),
        ];
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
