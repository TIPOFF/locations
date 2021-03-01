<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Location;
use Tipoff\Seo\Models\Webpage;

class CreateGmbDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('gmb_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Location::class);
            $table->string('name');
            $table->date('opened_at')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip', 5)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->foreignIdFor(Webpage::class)->nullable();

            $table->foreignIdFor(app('user'), 'creator_id')->nullable(); // User that requested the place data to be updated
            $table->timestamp('created_at');
        });
    }
}