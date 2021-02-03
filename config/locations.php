<?php

return [

    'model_class' => [

        'user' => \App\Models\User::class,

        'contact' => \App\Models\Contact::class,

        'tax' => \App\Models\Tax::class,

        'fee' => \App\Models\Fee::class,

        'order' => \App\Models\Order::class,

        'review' => \App\Models\Review::class,

        'insight' => \App\Models\Insight::class,

        'feedback' => \App\Models\Feedback::class,

        'room' => \App\Models\Room::class,

        'signature' => \App\Models\Signature::class,

        'competitor' => \App\Models\Competitor::class,

        'snapshot' => \App\Models\Snapshot::class,

        'slot' => \App\Models\Slot::class,

        'booking' => \App\Models\Booking::class,

        'theme' => \App\Models\Theme::class,

        'image' => \DrewRoberts\Media\Models\Image::class, // https://github.com/drewroberts/media

        'video' => \DrewRoberts\Media\Models\Video::class, // https://github.com/drewroberts/media

    ],

    'service' => [

        'calendar' => \App\Services\CalendarService::class

    ]

];
