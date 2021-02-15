<?php

declare(strict_types=1);

namespace Tipoff\Locations\Commands;

use Illuminate\Console\Command;

class LocationsCommand extends Command
{
    public $signature = 'locations';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
