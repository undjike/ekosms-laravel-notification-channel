<?php

namespace Undjike\EkoSmsNotificationChannel\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Undjike\EkoSmsNotificationChannel\EkoSmsMessage;
use Undjike\EkoSmsNotificationChannel\EkoSmsServiceProvider;

class SendMessageRequest
{
    /**
     * @throws GuzzleException
     * @noinspection PhpUndefinedFunctionInspection
     */
    public static function execute(EkoSmsMessage $message, array $addressees, array $auth = null): ResponseInterface
    {
        $client = new Client(['base_uri' => EkoSmsServiceProvider::BASE_URL]);

        $auth ??= [
            'username' => config('services.ekosms.username'),
            'password' => config('services.ekosms.password')
        ];

        $data = $auth + [
            'msisdn' => implode(',', $addressees),
            'sender' => $message->getSender(),
            'msg' => $message->getBody(),
            'encoding' => $message->getEncoding(),
            'remoteId' => $message->getRemoteId(),
            'allowunicode' => $message->unicodeAllowed()
        ];

        return $client->post('/messages', ['body' => $data]);
    }
}
