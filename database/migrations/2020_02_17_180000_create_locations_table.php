<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index(); // Will need to also check for unique slug in Page resource.
            $table->foreignIdFor(app('page'))->unique(); // Content for website frontend. Automatically created with Location creation.
            $table->string('name')->unique(); // Internal reference name
            $table->string('abbreviation', 4)->unique(); // 3 digit abbreviation (all caps) for location. Option to add 4th digit character if necessary.
            $table->string('title_part')->nullable(); // For when have more than one location in a market, this is used to generate formal title.
            $table->foreignIdFor(app('market'));
            $table->foreignIdFor(app('timezone'));
            $table->foreignIdFor(app('domestic_address'))->nullable();
            $table->foreignIdFor(app('phone'))->nullable();
            $table->foreignIdFor(app('user'), 'manager_id')->nullable();
            $table->foreignIdFor(app('email_address'), 'contact_email_id')->nullable();
            $table->date('closed_at')->nullable();

            $table->foreignIdFor(app('gmb_account'))->nullable();
            $table->string('gmb_location')->nullable()->unique(); // GMB ID for API. Will be used to update all the other fields below.

            $table->string('maps_url')->nullable()->unique(); // URL for location's Google My Business / Google Maps page.
            $table->string('review_url')->nullable()->unique(); // URL for a new review at the location.

            $table->smallInteger('aggregate_reviews')->nullable(); // Total aggregate number of Reviews for Location
            $table->unsignedDecimal('aggregate_rating', 2, 1)->nullable(); // Aggregate Review Rating for Location

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
