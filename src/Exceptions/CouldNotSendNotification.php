<?php

namespace Undjike\EkoSmsNotificationChannel\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError($response): CouldNotSendNotification
    {
        return new static("EkoSMS service responded with an error: $response");
    }
}
