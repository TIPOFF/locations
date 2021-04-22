<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use Illuminate\Database\Eloquent\Model;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class MarketAnnouncement extends Model
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
