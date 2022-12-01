<?php

namespace App\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ISMOExternalAPIClient
{
    /**
     * @var Client $client
     */
    private static $client;

    public static function getClient()
    {
        if (!isset(self::$client)) {
            self::$client = new Client([
                'base_uri' => config('ismo_external_api.base_url'),
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Authorization' => config('ismo_external_api.auth_token'),
                ],
            ]);
        }

        return self::$client;
    }
}
