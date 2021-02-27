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

            // Remaining fields updated from GMB so have one place as source of truth
            $table->string('title')->nullable()->unique(); // Location Title for display from GMB.
            $table->date('opened_at')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip', 5)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('monday_open')->nullable();
            $table->string('monday_close')->nullable();
            $table->string('tuesday_open')->nullable();
            $table->string('tuesday_close')->nullable();
            $table->string('wednesday_open')->nullable();
            $table->string('wednesday_close')->nullable();
            $table->string('thursday_open')->nullable();
            $table->string('thursday_close')->nullable();
            $table->string('friday_open')->nullable();
            $table->string('friday_close')->nullable();
            $table->string('saturday_open')->nullable();
            $table->string('saturday_close')->nullable();
            $table->string('sunday_open')->nullable();
            $table->string('sunday_close')->nullable();
            $table->string('maps_url')->nullable()->unique(); // URL for location's Google My Business / Google Maps page.
            $table->string('review_url')->nullable()->unique(); // URL for a new review at the location.
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('place_location')->nullable()->unique(); // Google Places ID
            
            $table->smallInteger('gmb_reviews')->nullable(); // Number of Google Reviews for Location
            $table->unsignedDecimal('gmb_rating', 2, 1)->nullable(); // Google Review Aggregate for Location

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
