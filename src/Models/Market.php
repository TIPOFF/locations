<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use DrewRoberts\Media\Traits\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    /*@todo since we remove slug, what would be the field  binding the model?*/
    /*public function getRouteKeyName()
    {
        return 'slug';
    }*/

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Market $market) {
            $market->page->slug = $market->page->slug ?: Str::slug($market->city);
            $invalidSlugs = config('locations.invalid_slugs') ?? [];
            if (in_array($market->page->slug, $invalidSlugs)) {
                $market->page->slug = Str::slug("{$market->page->slug}-{$market->state->slug}");
            }
        });

        static::saving(function ($market) {
            if (empty($market->entered_at)) {
                $market->entered_at = '2016-01-01';
            }
            if (empty($market->timezone_id)) {
                // @todo refactor to fetch EST timezone and save it here
                $market->timezone_id = 1;
            }
        });

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNull('markets.closed_at') || $builder->whereDate('markets.closed_at', '>', date('Y-m-d'));
        });

        static::addGlobalScope('locationCount', function ($builder) {
            $builder->withCount('locations');
        });
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

    public function map()
    {
        return $this->belongsTo(app('image'), 'map_image_id');
    }

    public function page()
    {
        return $this->belongsTo(app('page'));
    }

    public function state()
    {
        return $this->belongsTo(app('state'));
    }

    /**
     * Get a string for the php timezone of the market.
     *
     * @return string
     */
    public function getPhpTzAttribute()
    {
        if ($this->timezone == 'CST') {
            return 'America/Chicago';
        }

        return 'America/New_York';
    }

    /*@todo the redirect attribute may be moved to blog package*/
    /*public function getPathAttribute()
    {
        if (isset($this->redirect)) {
            return $this->redirect;
        }

        return "/{$this->slug}";
    }*/

    public function getRoomsPathAttribute()
    {
        return "/{$this->page->slug}/rooms";
    }

    public function getPrecautionsPathAttribute()
    {
        return "/{$this->page->slug}/precautions";
    }

    public function getEmploymentPathAttribute()
    {
        return "/{$this->page->slug}/employment";
    }

    public function getTeamBuildingPathAttribute()
    {
        return "/{$this->page->slug}/team-building";
    }

    public function getOnTheRunPathAttribute()
    {
        return "/{$this->page->slug}/on-the-run";
    }

    public function getPartiesPathAttribute()
    {
        return "/{$this->page->slug}/private-parties";
    }

    public function getContactPathAttribute()
    {
        return "/{$this->page->slug}/contact";
    }

    public function getGiftsPathAttribute()
    {
        return "/{$this->page->slug}/gift-certificates";
    }

    public function getFaqPathAttribute()
    {
        return "/{$this->page->slug}/faq";
    }

    public function getBookingsPathAttribute()
    {
        return "/{$this->page->slug}/book-online";
    }

    public function getReservationsPathAttribute()
    {
        return "/{$this->page->slug}/reservations";
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
