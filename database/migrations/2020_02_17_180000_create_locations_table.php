<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Market;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->string('name')->unique(); // Internal reference name
            $table->string('abbreviation', 4)->unique(); // 3 digit abbreviation (all caps) for location. Option to add 4th digit character if necessary.
            $table->string('title_part')->nullable(); // For when have more than one location in a market, this is used to generate formal title.
            $table->string('timezone'); // Informal symbol such as EST or CST
            $table->foreignIdFor(Market::class);
            $table->foreignIdFor(app('user'), 'manager_id')->nullable();
            $table->string('contact_email');
            $table->date('closed_at')->nullable();
            
            $table->foreignIdFor(app('image'))->nullable(); // Cover image for location
            $table->foreignIdFor(app('image'), 'ogimage_id')->nullable(); // External open graph image id. Featured image for social sharing. Will default to image_id unless this is used.
            $table->foreignIdFor(app('video'))->nullable(); // Featured video for the location
            
            $table->foreignIdFor(app('gmb_account'));
            $table->string('gmb_location')->nullable()->unique(); // GMB ID for API. Will be used to update all the other fields below.

            $table->string('maps_url')->nullable()->unique(); // URL for location's Google My Business / Google Maps page.
            $table->string('review_url')->nullable()->unique(); // URL for a new review at the location.
            
            $table->smallInteger('reviews')->nullable(); // Number of Reviews for Location
            $table->unsignedDecimal('rating', 2, 1)->nullable(); // Aggregate Review Rating for Location

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
