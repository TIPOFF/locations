<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Location;

class CreateProfileLinksTable extends Migration
{
    public function up()
    {
        Schema::create('profile_links', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->string('type');

            $table->foreignIdFor(Location::class);
            $table->foreignIdFor(app('company'))->nullable();
            $table->foreignIdFor(app('webpage'))->nullable();
            $table->foreignIdFor(app('domain'))->nullable();
            $table->timestamps();
        });
    }
}
