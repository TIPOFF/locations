<?php

declare(strict_types=1);

namespace Tipoff\Locations\Exceptions;

use Tipoff\Locations\Models\Market;
use Exception;

class UnresolvedLocation extends Exception
{
    protected $market;

    public function __construct(Market $market)
    {
        parent::__construct("Could not resolve location for { $market->toJson() }");

        $this->market = $market;
    }

    public function render()
    {
        return view('website.markets.select', [
            'market' => $this->market,
            'html' => null,
            'seotitle' => $this->market->title,
            'seodescription' => "{ $this->market->title } has { $this->market->rooms->count() } different escape rooms and offers private escape games for groups & parties. Book your escape room today!",
            'ogtitle' => $this->market->title,
            'ogdescription' => "{ $this->market->title } has { $this->market->rooms->count() } different escape rooms and offers private escape games for groups & parties. Book your escape room today!",
            'canonical' => "https://thegreatescaperoom.com{ $this->market->bookings_path }",
            'image' => $this->market->image_id === null ? null : $this->market->image,
            'ogimage' => $this->market->ogimage_id === null ? url('public/img/ogimage.png') : $this->market->ogimage,
        ]);
    }
}
