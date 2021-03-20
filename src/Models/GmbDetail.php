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

    const UPDATED_AT = null;

    public function location()
    {
        return $this->belongsTo(app('location'));
    }

    public function webpage()
    {
        return $this->belongsTo(app('webpage'));
    }
}
