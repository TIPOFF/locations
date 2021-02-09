<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketsTable extends Migration
{
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->string('name')->unique();
            $table->string('title')->unique(); // Market Title for Display. Used publicly.
            $table->string('state'); // Just the 2-digit abbreviation.
            $table->string('timezone');
            $table->boolean('corporate')->default(true);
            $table->string('redirect')->nullable();

            $table->text('content')->nullable(); // Market specific content. Written in Markdown.
            $table->date('entered_at'); // Date first location opened in the market.
            $table->date('closed_at')->nullable();

            $table->text('rooms_content')->nullable(); // Market specific content for /escape-rooms page. Written in Markdown.
            $table->text('faq_content')->nullable(); // Frequently Asked Questions about the market (such as where to park at each location in the market). Written in Markdown.
            $table->text('competitors_content')->nullable(); // Nice paragraph about the other escape rooms in each market. First used on /escape-rooms page. Written in Markdown.

            $table->foreignIdFor(app('image'))->nullable(); // Cover image for market
            $table->foreignIdFor(app('image'), 'ogimage_id')->nullable(); // External open graph image id. Featured image for social sharing. Will default to image_id unless this is used.
            $table->foreignIdFor(app('image'), 'map_image_id')->nullable(); // Image of location map for the market.
            $table->foreignIdFor(app('video'))->nullable(); // Featured video for the market.

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
