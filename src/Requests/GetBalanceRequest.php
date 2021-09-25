<?php

namespace Undjike\EkoSmsNotificationChannel\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Undjike\EkoSmsNotificationChannel\EkoSmsServiceProvider;

class GetBalanceRequest
{
    /**
     * @throws GuzzleException
     * @noinspection PhpUndefinedFunctionInspection
     */
    public static function execute(array $auth = null): ResponseInterface
    {
        $client = new Client(['base_uri' => EkoSmsServiceProvider::BASE_URL]);

        $auth ??= [
            'username' => config('services.ekosms.username'),
            'password' => config('services.ekosms.password')
        ];

        return $client->get('balance', ['query' => $auth]);
    }
}
