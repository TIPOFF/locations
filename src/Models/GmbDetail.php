<?php

declare(strict_types=1);

namespace Tipoff\Locations\Models;

use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class GmbDetail extends BaseModel
{
    use HasPackageFactory;
    use HasCreator;
    use HasUpdater;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function webpage()
    {
        return $this->belongsTo(app('webpage'));
    }
}
