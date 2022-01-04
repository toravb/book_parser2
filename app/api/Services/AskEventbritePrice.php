<?php

namespace App\Services;

use App\Event;
use Exception;
use Illuminate\Support\Facades\Http;

class AskEventbritePrice
{
    const BASE_URL = 'https://www.eventbriteapi.com/v3/events/';
    const API_METHOD = '/ticket_classes/';
    private $apiKey;

    public function __construct()
    {

        $this->apiKey = config('app.eventbrite_api_key');
    }

    public function askPrice($link, $stajEventId)
    {
        preg_match('/\d*\?/', $link, $matches);

        $eventId = str_replace('?', '', $matches[0]);

        $url = self::BASE_URL . (int)$eventId . self::API_METHOD;

        $response = Http::withToken($this->apiKey)->get($url);
        if ($response->failed()) {
            Event::where('id', $stajEventId)->update(['eventbrite_price' => null]);
            throw new Exception('Status: ' . $response['error'] . ' Message: ' . $response['error_description']);
        }

        $price = $this->getPrice($response->json());
        return $price;
    }

    public function getPrice($response)
    {
        $costs = [];

        foreach ($response['ticket_classes'] as $ticket) {
            if ($ticket['cost'] !== null) {
                $costs[] = $ticket['cost']['value'];
            }
        }

        if (count($costs) !== 0) {
            $minPrice = min($costs);

            $minPriceDisplay = '';

            foreach ($response['ticket_classes'] as $ticket) {
                if ($ticket['cost'] !== null) {
                    if ($minPrice === $ticket['cost']['value']) {
                        $minPriceDisplay = $ticket['cost']['display'];
                    }
                }
            }

            $price = 'from ' . $minPriceDisplay;
            return $price;
        }

        if ($response['ticket_classes'][0]['donation'] === true) {
            return 'donation';
        }

        if ($response['ticket_classes'][0]['free'] === true) {
            return 'free';
        }

        return null;
    }
}
