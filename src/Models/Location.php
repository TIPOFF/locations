<?php namespace Tipoff\Locations\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected $appends = [
        'php_tz',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            if (auth()->check()) {
                $location->creator_id = auth()->id();
            }
        });

        static::saving(function ($location) {
            if (empty($location->market_id)) {
                throw new \Exception('A location must be in a market.');
            }
            if (auth()->check()) {
                $location->updater_id = auth()->id();
            }
            if (empty($location->timezone)) {
                $location->timezone = 'EST';
            }
            if (empty($location->booking_cutoff)) {
                $location->booking_cutoff = 10;
            }
            if (empty($location->contact_email)) {
                $location->contact_email = $location->slug . '@thegreatescaperoom.com';
            }
            if (empty($location->abbreviation)) {
                do {
                    $abbreviation = Str::upper(Str::limit(Str::slug($location->name), 3)) . Str::upper(Str::random(1));
                } while (self::where('abbreviation', $abbreviation)->first()); //check if the token already exists and if it does, try again
                $location->abbreviation = $abbreviation;
            }
        });

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNull('locations.closed_at') || $builder->whereDate('locations.closed_at', '>=', date('Y-m-d'));
        });
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function manager()
    {
        return $this->belongsTo(config('locations.model_class.user'), 'manager_id');
    }

    public function contacts()
    {
        return $this->hasMany(config('locations.model_class.contact'));
    }

    public function bookingTax()
    {
        return $this->belongsTo(config('locations.model_class.tax'), 'booking_tax_id');
    }

    public function productTax()
    {
        return $this->belongsTo(config('locations.model_class.tax'), 'product_tax_id');
    }

    public function bookingFee()
    {
        return $this->belongsTo(config('locations.model_class.fee'), 'booking_fee_id');
    }

    public function productFee()
    {
        return $this->belongsTo(config('locations.model_class.fee'), 'product_fee_id');
    }

    public function teamPhoto()
    {
        return $this->belongsTo(config('locations.model_class.image'), 'team_image_id');
    }

    public function users()
    {
        return $this->belongsToMany(config('locations.model_class.user'))->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(config('locations.model_class.order'));
    }

    public function reviews()
    {
        return $this->hasMany(config('locations.model_class.review'));
    }

    public function insights()
    {
        return $this->hasMany(config('locations.model_class.insight'));
    }

    public function feedbacks()
    {
        return $this->hasMany(config('locations.model_class.feedback'));
    }

    public function rooms()
    {
        return $this->hasMany(config('locations.model_class.room'));
    }

    public function creator()
    {
        return $this->belongsTo(config('locations.model_class.user'), 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(config('locations.model_class.user'), 'updater_id');
    }

    public function signatures()
    {
        return $this->hasManyThrough(config('locations.model_class.signature'), config('locations.model_class.room'));
    }

    public function competitor()
    {
        return $this->hasOne(config('locations.model_class.competitor'));
    }

    public function snapshots()
    {
        return $this->hasManyThrough(config('locations.model_class.snapshot'), config('locations.model_class.competitor'));
    }

    public function slots()
    {
        return $this->hasManyThrough(config('locations.model_class.slot'), config('locations.model_class.room'));
    }

    public function getSelectorTitleAttribute()
    {
        if ($this->market->locations_count > 1) {
            return "{$this->market->state} - {$this->market->name} ({$this->name})";
        } else {
            return "{$this->market->state} - {$this->name}";
        }
    }

    public function getStreetAddressAttribute()
    {
        $add2 = '';
        if ($this->address2) {
            $add2 = ' ' . $this->address2;
        }

        return "{$this->address}{$add2}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->street_address}, {$this->city}, {$this->state} {$this->zip}";
    }

    public function getPathAttribute()
    {
        return "/{$this->market->slug}";
    }

    public function getWaiverPathAttribute()
    {
        return "/waiver/{$this->slug}";
    }

    public function getRoomsPathAttribute()
    {
        return "/{$this->market->slug}/rooms";
    }

    public function getPrecautionsPathAttribute()
    {
        return "/{$this->market->slug}/precautions";
    }

    public function getEmploymentPathAttribute()
    {
        return "/{$this->market->slug}/employment";
    }

    public function getTeamBuildingPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/team-building";
        }

        return "/{$this->market->slug}/team-building/{$this->slug}";
    }

    public function getOnTheRunPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/on-the-run";
        }

        return "/{$this->market->slug}/on-the-run/{$this->slug}";
    }

    public function getPartiesPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/private-parties";
        }

        return "/{$this->market->slug}/private-parties/{$this->slug}";
    }

    public function getContactPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/contact";
        }

        return "/{$this->market->slug}/contact/{$this->slug}";
    }

    public function getSubmitFormUrlAttribute()
    {
        if (config('app.env') == 'production') {
            return 'https://thegreatescaperoom.com/api/contact/' . $this->slug;
        }

        return 'https://tger.dev/api/contact/' . $this->slug;
    }

    public function getGiftsPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/gift-certificates";
        }

        return "/{$this->market->slug}/gift-certificates/{$this->slug}";
    }

    public function getFaqPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/faq";
        }

        return "/{$this->market->slug}/faq/{$this->slug}";
    }

    public function getBookingsPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/book-online";
        }

        return "/{$this->market->slug}/book-online/{$this->slug}";
    }

    public function getReservationsPathAttribute()
    {
        if ($this->market->locations_count === 1) {
            return "/{$this->market->slug}/reservations";
        }

        return "/{$this->market->slug}/reservations/{$this->slug}";
    }

    public function getPhoneLinkAttribute()
    {
        return 'tel:' . preg_replace("/[^0-9]/", "", $this->phone);
    }

    public function getDirectionsUrlAttribute()
    {
        return 'https://www.google.com/maps/dir/+Your+location/+' . Str::of($this->title)->replace(' ', '+') . ',@' . $this->latitude . ',' . $this->longitude;
    }

    public function getBookingsYesterdayAttribute()
    {
        return config('locations.model_class.booking')::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->count();
    }

    public function getRevenueBookedYesterdayAttribute()
    {
        return number_format((config('locations.model_class.booking')::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('amount')
            +
                config('locations.model_class.booking')::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('total_fees')) / 100, 2);
    }

    public function getBookingsLastWeekAttribute()
    {
        return config('locations.model_class.booking')::week()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->count();
    }

    public function getRevenueBookedLastWeekAttribute()
    {
        return number_format((config('locations.model_class.booking')::week()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('amount')
            +
                config('locations.model_class.booking')::week()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('total_fees')) / 100, 2);
    }

    public function stripeEnabled()
    {
        return $this->stripe_publishable && $this->stripe_secret;
    }

    /**
     * Find location by slot or slot number.
     *
     * @param mixed $slot
     * @return Location|null
     */
    public function findBySlot($slot)
    {
        $slotModel = config('locations.model_class.slot');

        if ($slot instanceof $slotModel) {
            return $slot->room->location;
        }

        $locationId = app(config('locations.service_class.calendar'))->getLocationIdBySlotNumber($slot);

        return self::find($locationId);
    }

    /**
     * Find location by slot or slot number or throw model not found exception.
     *
     * @param mixed $slot
     * @return Location|null
     */
    public function findBySlotOrFail($slot)
    {
        $slotModel = config('locations.model_class.slot');

        if ($slot instanceof $slotModel) {
            return $slot->room->location;
        }

        $locationId = app(config('locations.service_class.calendar'))->getLocationIdBySlotNumber($slot);

        return self::findOrFail($locationId);
    }

    /**
     * Find existing or virtual slot.
     *
     * @param string $slotNumber
     * @return Slot|null
     */
    public function findOrGenerateSlot($slotNumber)
    {
        $slot = config('locations.model_class.slot')::where('slot_number', $slotNumber)
            ->location($this)
            ->first();

        // Virtual  Slots
        if (! $slot) {
            $calendarService = app(config('locations.service_class.calendar'));
            $date = $calendarService->generateDateFromSlotNumber($slotNumber);
            $recurringSchedules = $calendarService->getLocationRecurringScheduleForDateRange($this->id, $date, $date);
            $slots = new SlotsCollection(); //@TODO phuclh Need to refactor this later since we do not have this collection in this package.
            $slot = $slots
                ->applyRecurringSchedules($recurringSchedules, $date)
                ->where('slot_number', $slotNumber);
        }

        return $slot;
    }

    /**
     * Get current date time at location.
     *
     * @return Carbon
     */
    public function getCurrentDateTime()
    {
        return Carbon::now($this->getPhpTzAttribute());
    }

    /**
     * Get a string for the php timezone of the location.
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

    /**
     * Get data/time at location.
     *
     * @return Carbon
     */
    public function currentDateTime()
    {
        return Carbon::now()->setTimeZone($this->php_tz);
    }

    /**
     * Apply location timezone to data/time.
     *
     * @param Carbon|string $dateTime
     * @return Carbon
     */
    public function toLocalDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->setTimeZone($this->php_tz);
    }

    /**
     * Apply UTC timezone to local data/time.
     *
     * @param Carbon|string $dateTime
     * @return Carbon
     */
    public function toUtclDateTime($dateTime)
    {
        return Carbon::parse($dateTime, $this->php_tz)->setTimeZone('UTC');
    }
}
