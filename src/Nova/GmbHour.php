<?php

declare(strict_types=1);

namespace Tipoff\Locations\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Tipoff\Support\Nova\BaseResource;

class GmbHour extends BaseResource
{
    public static $model = \Tipoff\Locations\Models\GmbHour::class;

    public static $orderBy = ['id' => 'asc'];

    public static $title = 'type';

    public static $search = [
        'id',
    ];

    public static $group = 'Escape Rooms';

    public function fieldsForIndex(NovaRequest $request)
    {
        return array_filter([
            ID::make()->sortable(),
        ]);
    }

    public function fields(Request $request)
    {
        return array_filter([
            nova('gmb_hour') ? BelongsTo::make('GmbHour', 'gmb_hour', nova('gmb_hour'))->required() : null,

            new Panel('Hours of Operation', $this->hoursFields()),

            new Panel('Data Fields', $this->dataFields()),
        ]);
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
