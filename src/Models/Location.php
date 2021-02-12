<?php namespace Tipoff\Locations\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasUpdater;

class Location extends BaseModel
{
    use HasFactory;
    use HasCreator;
    use HasUpdater;

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

        static::saving(function ($location) {
            if (empty($location->market_id)) {
                throw new \Exception('A location must be in a market.');
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
        return $this->belongsTo(app('market'));
    }

    public function manager()
    {
        return $this->belongsTo(app('user'), 'manager_id');
    }

    public function contacts()
    {
        return $this->hasMany(app('contact'));
    }

    public function bookingTax()
    {
        return $this->belongsTo(app('tax'), 'booking_tax_id');
    }

    public function productTax()
    {
        return $this->belongsTo(app('tax'), 'product_tax_id');
    }

    public function bookingFee()
    {
        return $this->belongsTo(app('fee'), 'booking_fee_id');
    }

    public function productFee()
    {
        return $this->belongsTo(app('fee'), 'product_fee_id');
    }

    public function teamPhoto()
    {
        return $this->belongsTo(app('image'), 'team_image_id');
    }

    public function users()
    {
        return $this->belongsToMany(app('user'))->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(app('order'));
    }

    public function reviews()
    {
        return $this->hasMany(app('review'));
    }

    public function insights()
    {
        return $this->hasMany(app('insight'));
    }

    public function feedbacks()
    {
        return $this->hasMany(app('feedback'));
    }

    public function rooms()
    {
        return $this->hasMany(app('room'));
    }

    public function signatures()
    {
        return $this->hasManyThrough(app('signature'), app('room'));
    }

    public function competitor()
    {
        return $this->hasOne(app('competitor'));
    }

    public function snapshots()
    {
        return $this->hasManyThrough(app('snapshot'), app('competitor'));
    }

    public function slots()
    {
        return $this->hasManyThrough(app('slot'), app('room'));
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
        /** @var Model $bookingModel */
        $bookingModel = app('booking');

        return $bookingModel::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->count();
    }

    public function getRevenueBookedYesterdayAttribute()
    {
        /** @var Model $bookingModel */
        $bookingModel = app('booking');

        return number_format(($bookingModel::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('amount')
            +
                $bookingModel::yesterday()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('total_fees')) / 100, 2);
    }

    public function getBookingsLastWeekAttribute()
    {
        /** @var Model $bookingModel */
        $bookingModel = app('booking');

        return $bookingModel::week()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->count();
    }

    public function getRevenueBookedLastWeekAttribute()
    {
        /** @var Model $bookingModel */
        $bookingModel = app('booking');

        return number_format(($bookingModel::week()
            ->whereHas('order', function (Builder $query) {
                $query->where('location_id', $this->id);
            })->sum('amount')
            +
                $bookingModel::week()
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
        $slotModel = app('slot');

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
        $slotModel = app('slot');

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
     * @return mixed
     */
    public function findOrGenerateSlot($slotNumber)
    {
        /** @var Model $slotModel */
        $slotModel = app('slot');

        $slot = $slotModel::where('slot_number', $slotNumber)
            ->location($this)
            ->first();

        // Virtual  Slots
        if (! $slot) {
            /** @var string $slotCollection */
            $slotCollection = config('locations.collection_class.slot'); //@TODO phuclh Need to refactor this later since we do not have this collection in this package.

            $calendarService = app(config('locations.service_class.calendar'));
            $date = $calendarService->generateDateFromSlotNumber($slotNumber);
            $recurringSchedules = $calendarService->getLocationRecurringScheduleForDateRange($this->id, $date, $date);
            $slots = new $slotCollection;
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
