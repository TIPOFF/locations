<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use Assert\Assert;
use Carbon\Carbon;
use DrewRoberts\Blog\Models\Page;
use DrewRoberts\Media\Traits\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Tipoff\Addresses\Models\Timezone;
use Tipoff\Support\Contracts\Checkout\OrderInterface;
use Tipoff\Support\Contracts\Checkout\OrderItemInterface;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class Location extends BaseModel
{
    use HasPackageFactory;
    use HasMedia;
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

        static::creating(function (Location $location) {
            $location->slug = $location->slug ?: Str::slug($location->name);
            if ($location->page_id) {
                $location->page->update($location->pageFields());
            } else {
                $location->page()->associate(
                    Page::query()->create($location->pageFields())
                );
            }
        });

        static::saving(function (Location $location) {
            Assert::lazy()
                ->that($location->market_id)->notEmpty('A location must be in a market.')
                ->verifyNow();
            $location->timezone_id = $location->timezone_id ?: $location->market->timezone->id;

            if (empty($location->abbreviation)) {
                do {
                    $abbreviation = Str::upper(Str::substr(Str::slug($location->name), 0, 3)) . Str::upper(Str::random(1));
                } while (self::where('abbreviation', $abbreviation)->first()); //check if the token already exists and if it does, try again
                $location->abbreviation = strtoupper($abbreviation);
            } else {
                $location->abbreviation = strtoupper($location->abbreviation);
            }
        });

        static::updating(function (Location $location) {
            $location->page->update($location->pageFields());
        });

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNull('locations.closed_at') || $builder->whereDate('locations.closed_at', '>=', date('Y-m-d'));
        });
    }

    private function pageFields(): array
    {
        return [
            'parent_id' => $this->market->page->id,
            'slug' => $this->slug,
            'title' => $this->title_part ?? $this->name,
        ];
    }

    public function market()
    {
        return $this->belongsTo(app('market'));
    }

    public function page()
    {
        return $this->belongsTo(app('page'));
    }

    public function timezone()
    {
        return $this->belongsTo(app('timezone'));
    }

    public function address()
    {
        return $this->belongsTo(app('domestic_address'), 'domestic_address_id');
    }

    public function phone()
    {
        return $this->belongsTo(app('phone'));
    }

    public function gmb_account()
    {
        return $this->belongsTo(app('gmb_account'), 'gmb_account_id');
    }

    public function gmb_detail()
    {
        return $this->hasOne(app('gmb_detail'));
    }

    public function gmb_hour()
    {
        return $this->hasOne(app('gmb_hour'))->latest();
    }

    public function manager()
    {
        return $this->belongsTo(app('user'), 'manager_id');
    }

    public function email()
    {
        return $this->belongsTo(app('email_address'), 'contact_email_id');
    }

    public function contacts()
    {
        return $this->hasMany(app('contact'));
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
        return $this->belongsTo(app('competitor'));
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

    public function getContactEmailAddressAttribute()
    {
        return $this->contactEmail()->email;
    }

    public function getStreetAddressAttribute()
    {
        $add2 = '';
        if ($this->address()->address_line_2) {
            $add2 = ' ' . $this->address()->address_line_2;
        }

        return "{$this->address()->address_line_1}{$add2}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address()->address_line_1}, {$this->address()->city()->title}, {$this->address()->zip()->state()->title}, {$this->address()->zip()->code}";
    }

    public function getPathAttribute()
    {
        return "/{$this->market->slug}";
    }

    public function getPhoneLinkAttribute()
    {
        return 'tel:' . preg_replace("/[^0-9]/", "", $this->phone()->full_number);
    }

    public function getDirectionsUrlAttribute()
    {
        return 'https://www.google.com/maps/dir/+Your+location/+' . Str::of($this->title)->replace(' ', '+') . ',@' . $this->latitude . ',' . $this->longitude;
    }

    public function getBookingsYesterdayAttribute()
    {
        /** @var OrderInterface $service */
        $service = findService(OrderInterface::class);
        if ($service) {
            return $service::itemFilter()
                ->bySellableType(morphClass('booking') ?? '')
                ->byLocation($this->id)
                ->yesterday()
                ->apply()
                ->count();
        }

        return 0;
    }

    public function getRevenueBookedYesterdayAttribute()
    {
        /** @var OrderInterface $service */
        $service = findService(OrderInterface::class);
        if ($service) {
            $amount = $service::itemFilter()
                ->bySellableType(morphClass('booking') ?? '')
                ->byLocation($this->id)
                ->yesterday()
                ->apply()
                ->sum(function (OrderItemInterface $orderItem) {
                    return $orderItem->getAmountTotal()->getDiscountedAmount();
                });

            return number_format($amount / 100, 2);
        }

        return 0;
    }

    public function getBookingsLastWeekAttribute()
    {
        /** @var OrderInterface $service */
        $service = findService(OrderInterface::class);
        if ($service) {
            return $service::itemFilter()
                ->bySellableType(morphClass('booking') ?? '')
                ->byLocation($this->id)
                ->week()
                ->apply()
                ->count();
        }

        return 0;
    }

    public function getRevenueBookedLastWeekAttribute()
    {
        /** @var OrderInterface $service */
        $service = findService(OrderInterface::class);
        if ($service) {
            $amount = $service::itemFilter()
                ->bySellableType(morphClass('booking') ?? '')
                ->byLocation($this->id)
                ->week()
                ->apply()
                ->sum(function (OrderItemInterface $orderItem) {
                    return $orderItem->getAmountTotal()->getDiscountedAmount();
                });

            return number_format($amount / 100, 2);
        }

        return 0;
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

    public function getCurrentDateTime()
    {
        return Carbon::now($this->getPhpTzAttribute());
    }

    public function getPhpTzAttribute()
    {
        return $this->timezone->php;
    }

    public function currentDateTime()
    {
        return Carbon::now()->setTimeZone($this->php_tz);
    }

    public function toLocalDateTime($dateTime)
    {
        return Carbon::parse($dateTime)->setTimeZone($this->php_tz);
    }

    public function toUtcDateTime($dateTime)
    {
        return Carbon::parse($dateTime, $this->php_tz)->setTimeZone('UTC');
    }
}
