<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(app('page'))->unique(); // Content & slug for website frontend
            $table->string('name')->unique();
            $table->string('title')->unique(); // Market Title for Display. Used publicly.
            $table->foreignIdFor(app('state'));
            $table->foreignIdFor(app('timezone'));
            $table->date('entered_at'); // Date first location opened in the market.
            $table->date('closed_at')->nullable();
            $table->string('redirect')->nullable(); // May move this to Page in drewroberts/blog
            $table->foreignIdFor(app('image'), 'map_image_id')->nullable(); // Image of location map for the market.

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
