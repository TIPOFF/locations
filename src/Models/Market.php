<?php namespace Tipoff\Locations\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
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

        static::creating(function ($market) {
            if (auth()->check()) {
                $market->creator_id = auth()->id();
            }
        });

        static::saving(function ($market) {
            if (auth()->check()) {
                $market->updater_id = auth()->id();
            }
            if (empty($market->entered_at)) {
                $market->entered_at = '2016-01-01';
            }
            if (empty($market->timezone)) {
                $market->timezone = 'EST';
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
        return $this->hasMany(Location::class);
    }

    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Location::class);
    }

    public function image()
    {
        return $this->belongsTo(\DrewRoberts\Media\Models\Image::class);
    }

    public function ogimage()
    {
        return $this->belongsTo(\DrewRoberts\Media\Models\Image::class, 'ogimage_id');
    }

    public function map()
    {
        return $this->belongsTo(\DrewRoberts\Media\Models\Image::class, 'map_image_id');
    }

    public function video()
    {
        return $this->belongsTo(\DrewRoberts\Media\Models\Video::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updater_id');
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

        $rooms = Room::whereIn('location_id', $locations->pluck('id'))->whereNull('closed_at')->get();

        return $this->title.' has '.$rooms->count().' different escape rooms and offers private escape games for groups & parties. Book your escape room today!';
    }

    public function findAllThemes()
    {
        $locations = $this->locations;

        // Get all rooms for those locations
        $rooms = Room::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = Theme::whereIn('id', $rooms->pluck('theme_id'))
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

        // Get all rooms for those locations
        $rooms = Room::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = Theme::whereIn('id', $rooms->pluck('theme_id'))
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

        // Get all rooms for those locations
        $rooms = Room::whereIn('location_id', $locations->pluck('id'))
            ->whereNull('closed_at')
            ->orderByDesc('priority')
            ->get();

        $roomPriority = $rooms->pluck('priority', 'theme_id');

        // Get the themes, ordered by rooms->priority exclude closed themes and remove duplicates
        $themes = Theme::whereIn('id', $rooms->pluck('theme_id'))
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
