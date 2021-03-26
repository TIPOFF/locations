<?php

use Illuminate\Support\Facades\Route;
use Tipoff\Locations\Http\Controllers\LocationController;
use Tipoff\Locations\Http\Controllers\MarketController;
use Tipoff\Locations\Http\Middleware\ResolveLocation;

Route::middleware(config('tipoff.web.middleware_group'))
    ->prefix(config('tipoff.web.uri_prefix'))
    ->group(function () {

        Route::get('{market}/{location}/detail', LocationController::class)
            ->name('location');

        Route::get('{market}/detail', MarketController::class)
            ->name('market');
    });
