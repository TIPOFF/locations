<?php

declare(strict_types=1);

namespace Tipoff\Locations\Commands;

use Google_Service_MyBusiness;
use Illuminate\Console\Command;
use Tipoff\GoogleApi\Facades\GoogleOauth;
use Tipoff\GoogleApi\GoogleServices;
use Tipoff\Locations\Models\Location;

class SyncLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync information from Google My Business to the location table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // @todo Needs Refactoring
        $accounts = ['accounts/108772742689976468845/locations', 'accounts/116666006358174413896/locations'];

        // Get access token from Google Oauth package.
        $accessToken = GoogleOauth::accessToken('my-business');

        /** @var GoogleServices $googleServices */
        $googleServices = app(GoogleServices::class)->setAccessToken($accessToken);

        $myBusiness = $googleServices->myBusiness();

        foreach ($accounts as $account) {
            $gmblocations = $myBusiness->accounts_locations->get($account)->toSimpleObject()->locations;

            foreach ($gmblocations as $gmb) {
                $gmbid = substr($gmb['name'], strrpos($gmb['name'], '/') + 1);
                $location = Location::where('gmb_location', $gmbid)->first();
                if ($location) {
                    $location->title = $gmb['locationName'];
                    if (isset($gmb['openInfo']['openingDate']['day'])) {
                        $location->opened_at = $gmb['openInfo']['openingDate']['year'].'-'.$gmb['openInfo']['openingDate']['month'].'-'.$gmb['openInfo']['openingDate']['day'];
                    }
                    $location->address = $gmb['address']['addressLines'][0];
                    if (isset($gmb['address']['addressLines'][1])) {
                        $location->address2 = $gmb['address']['addressLines'][1];
                    }
                    $location->city = $gmb['address']['locality'];
                    $location->state = $gmb['address']['administrativeArea'];
                    $location->zip = substr($gmb['address']['postalCode'], 0, 5);
                    $location->phone = $gmb['primaryPhone'];
                    if (isset($gmb['regularHours'])) {
                        foreach ($gmb['regularHours']['periods'] as $t) {
                            if ($t['openDay'] == 'SUNDAY') {
                                $location->sunday_open = $t['openTime'];
                                $location->sunday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'MONDAY') {
                                $location->monday_open = $t['openTime'];
                                $location->monday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'TUESDAY') {
                                $location->tuesday_open = $t['openTime'];
                                $location->tuesday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'WEDNESDAY') {
                                $location->wednesday_open = $t['openTime'];
                                $location->wednesday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'THURSDAY') {
                                $location->thursday_open = $t['openTime'];
                                $location->thursday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'FRIDAY') {
                                $location->friday_open = $t['openTime'];
                                $location->friday_close = $t['closeTime'];
                            }
                            if ($t['openDay'] == 'SATURDAY') {
                                $location->saturday_open = $t['openTime'];
                                $location->saturday_close = $t['closeTime'];
                            }
                        }
                    }
                    $location->review_url = $gmb['metadata']['newReviewUrl'];
                    $location->maps_url = $gmb['metadata']['mapsUrl'];
                    if (isset($gmb['latlng'])) {
                        $location->latitude = $gmb['latlng']['latitude'];
                        $location->longitude = $gmb['latlng']['longitude'];
                    }
                    $location->place_location = $gmb['locationKey']['placeId'];

                    $location->save();
                }
            }
        }
    }
}
