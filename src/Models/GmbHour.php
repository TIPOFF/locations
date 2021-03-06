<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class GmbHour extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    public $timestamps = false;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
