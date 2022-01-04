<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class AskGoogleMapApi
{
    const GET_LOCATION_METHOD = 'geocode/';
    const BASE_URL = 'https://maps.googleapis.com/maps/api/';
    const OUTPUT_DATA_TYPE = 'json';
    private $apikey;

    public function __construct()
    {
        $this->apikey = config('app.google_map_key');
    }

    public function getLocationFromAddress(string $address)
    {
        $requestUrl = self::BASE_URL . self::GET_LOCATION_METHOD . self::OUTPUT_DATA_TYPE;
        $address = str_replace(" ", "+", $address);

        $response = Http::get($requestUrl, [
            'address' => $address,
            'key' => $this->apikey,
        ]);


        return $response->json();
    }
}
