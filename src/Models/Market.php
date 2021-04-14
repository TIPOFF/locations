<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use DrewRoberts\Blog\Models\Layout;
use DrewRoberts\Blog\Models\Page;
use DrewRoberts\Media\Traits\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Tipoff\Addresses\Models\Timezone;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class Market extends BaseModel
{
    use HasPackageFactory;
    use HasMedia;
    use HasCreator;
    use HasUpdater;

    protected $casts = [
        'entered_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Market $market) {
            $market->slug = $market->slug ?: Str::slug($market->city);
            if ($market->page_id) {
                $market->page->update($market->pageFields());
            } else {
                $market->page()->associate(
                    Page::query()->create(
                        array_merge($market->pageFields(), [
                            'layout_id' => Layout::query()->where('view', 'locations::page.market.base')->firstOrFail()->id,
                        ])
                    )
                );
            }
        });

        static::saving(function (Market $market) {
            $market->title = $market->title ?: $market->name;
            $market->entered_at = $market->entered_at ?: '2016-01-01';
            $market->timezone_id = $market->timezone_id ?: Timezone::fromAbbreviation('EST')->id;
        });

        static::updating(function (Market $market) {
            $market->page->update($market->pageFields());
        });

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNull('markets.closed_at') || $builder->whereDate('markets.closed_at', '>', date('Y-m-d'));
        });

        static::addGlobalScope('locationCount', function ($builder) {
            $builder->withCount('locations');
        });
    }

    private function pageFields(): array
    {
        return [
            'parent_id' => null,
            'slug' => $this->slug,
            'title' => $this->title,
            'is_location' => true,
        ];
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function state()
    {
        return $this->belongsTo(app('state'));
    }

    public function timezone()
    {
        return $this->belongsTo(app('timezone'));
    }

    public function map()
    {
        return $this->belongsTo(app('image'), 'map_image_id');
    }

    public function locations()
    {
        return $this->hasMany(app('location'));
    }

    public function competitors()
    {
        return $this->hasMany(app('competitor'));
    }

    public function rooms()
    {
        return $this->hasManyThrough(app('room'), app('location'));
    }

    public function getPhpTzAttribute()
    {
        return $this->timezone->php;
    }

    public function getPathAttribute()
    {
        if (isset($this->redirect)) {
            return $this->redirect;
        }

        return "/{$this->slug}";
    }

    public function getRoomsPathAttribute()
    {
        return "/{$this->slug}/rooms";
    }

    public function getPrecautionsPathAttribute()
    {
        return "/{$this->slug}/precautions";
    }

    public function getEmploymentPathAttribute()
    {
        return "/{$this->slug}/employment";
    }

    public function getTeamBuildingPathAttribute()
    {
        return "/{$this->slug}/team-building";
    }

    public function getOnTheRunPathAttribute()
    {
        return "/{$this->slug}/on-the-run";
    }

    public function getPartiesPathAttribute()
    {
        return "/{$this->slug}/private-parties";
    }

    public function getContactPathAttribute()
    {
        return "/{$this->slug}/contact";
    }

    public function getGiftsPathAttribute()
    {
        return "/{$this->slug}/gift-certificates";
    }

    public function getFaqPathAttribute()
    {
        return "/{$this->slug}/faq";
    }

    public function getBookingsPathAttribute()
    {
        return "/{$this->slug}/book-online";
    }

    public function getReservationsPathAttribute()
    {
        return "/{$this->slug}/reservations";
    }

    public function getDescriptionAttribute()
    {
        $locations = $this->locations;

        /** @var Model $roomModel */
        $roomModel = app('room');

        $rooms = $roomModel::whereIn('location_id', $locations->pluck('id'))->whereNull('closed_at')->get();

        return $this->title . ' has ' . $rooms->count() . ' different escape rooms and offers private escape games for groups & parties. Book your escape room today!';
    }

    public function findAllThemes()
    {
        $locations = $this->locations;

        /** @var Model $roomModel */
        $roomModel = app('room');

        /** @var Model $themeModel */
        $themeModel = app('theme');

        // Get all rooms for those locations
        $rooms = $roomModel::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = $themeModel::whereIn('id', $rooms->pluck('theme_id'))
            ->get()
            ->sortBy(function ($theme) use ($roomPriority) {
                return $roomPriority[$theme->id];
            });

        if ($themes->count() == 0) {
            return;
        }

        return $themes;
    }

    public function findEscapeThemes()
    {
        $locations = $this->locations;

        /** @var Model $roomModel */
        $roomModel = app('room');

        /** @var Model $themeModel */
        $themeModel = app('theme');

        // Get all rooms for those locations
        $rooms = $roomModel::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = $themeModel::whereIn('id', $rooms->pluck('theme_id'))
            ->where('scavenger_level', '<', 4)
            ->get()
            ->sortBy(function ($theme) use ($roomPriority) {
                return $roomPriority[$theme->id];
            });

        if ($themes->count() == 0) {
            return;
        }

        return $themes;
    }

    public function findScavengerThemes()
    {
        $locations = $this->locations;

        /** @var Model $roomModel */
        $roomModel = app('room');

        // Get all rooms for those locations
        $rooms = $roomModel::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        /** @var Model $themeModel */
        $themeModel = app('theme');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = $themeModel::whereIn('id', $rooms->pluck('theme_id'))
            ->where('scavenger_level', '>=', 4)
            ->get()
            ->sortBy(function ($theme) use ($roomPriority) {
                return $roomPriority[$theme->id];
            });

        if ($themes->count() == 0) {
            return;
        }

        return $themes;
    }
}
