<?php

namespace Undjike\EkoSmsNotificationChannel;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use Psr\Http\Message\StreamInterface;
use Undjike\EkoSmsNotificationChannel\Exceptions\CouldNotSendNotification;
use Undjike\EkoSmsNotificationChannel\Requests\GetBalanceRequest;
use Undjike\EkoSmsNotificationChannel\Requests\SendMessageRequest;

class EkoSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @throws CouldNotSendNotification
     * @throws GuzzleException
     * @throws Exception
     */
    public function send($notifiable, Notification $notification): void
    {
        if (!$recipient = $notifiable->routeNotificationFor('EkoSms')) {
            throw new CouldNotSendNotification('Your notifiable instance does not have function routeNotificationForEkoSms.');
        }

        if (!method_exists($notification, 'toEkoSms')) {
            throw new CouldNotSendNotification('Your need to define the toEkoSms method on your notification for it to be sent.');
        }

        $message = $notification->toEkoSms($notifiable);

        if (is_string($message)) {
            $content = trim($message);
            $message = EkoSmsMessage::create()->body($content);
        }

        if (!$message instanceof EkoSmsMessage) {
            throw new CouldNotSendNotification('Required string or EkoSmsMessage instance as the return type of toEkoSms.');
        }

        if (empty(trim($message->getBody()))) {
            throw new CouldNotSendNotification('Can\'t send a message with an empty body.');
        }

        if (!$recipient) {
            throw new CouldNotSendNotification('Can\'t send a message with no recipient.');
        }

        if (is_string($recipient)) {
            $recipient = [$recipient];
        }

        if (!is_array($recipient)) {
            throw new CouldNotSendNotification('Expected string or array as recipient.');
        }

        $response = SendMessageRequest::execute($message, $recipient);

        self::interpretResponse($response->getBody());
    }

    /**
     * @throws CouldNotSendNotification
     * @throws GuzzleException
     * @throws Exception
     */
    public static function balance(array $auth = null): int
    {
        $response = GetBalanceRequest::execute($auth);

        $data = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (isset($data['code'])) {
            self::respond([$data]);
        }

        if (!isset($data['amount'])) {
            throw new CouldNotSendNotification('Unable to parse the response.');
        }

        return (int) $data['amount'];
    }

    /**
     * @throws Exception
     * @throws CouldNotSendNotification
     */
    private static function interpretResponse(StreamInterface $getBody): void
    {
        $response = json_decode(
            $getBody->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (isset($response['code'])) {
            self::respond([$response]);
        }

        if (!isset($response['results'])) {
            throw new CouldNotSendNotification('Unable to parse the response.');
        }

        $results = $response['results'];

        self::respond($results);
    }

    /**
     * @param array $results
     * @throws CouldNotSendNotification
     */
    private static function respond(array $results): void
    {
        foreach ($results as $result) {
            $errorCode = (int)$result['code'];

            if ($errorCode === 0) {
                continue;
            }

            switch ($errorCode) {
                case -1:
                case 1001:
                    throw new CouldNotSendNotification('Not authenticated.');
                case -2:
                case -3:
                    throw new CouldNotSendNotification('Invalid phone number or operator.');
                case -4:
                    throw new CouldNotSendNotification('Unsupported destination country.');
                case -8:
                    throw new CouldNotSendNotification('Data coding scheme invalid.');
                case -9:
                    throw new CouldNotSendNotification('Service ID not found.');
                case -10:
                    throw new CouldNotSendNotification('Message too long.');
                case -11:
                    throw new CouldNotSendNotification('Not enough balance.');
                default:
                    throw new CouldNotSendNotification('Error occurred.');
            }
        }
    }
}
