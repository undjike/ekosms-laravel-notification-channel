<?php

namespace Undjike\EkoSmsNotificationChannel\Tests;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Undjike\EkoSmsNotificationChannel\EkoSmsChannel;
use Undjike\EkoSmsNotificationChannel\Exceptions\CouldNotSendNotification;

class GetBalanceRequestTest extends TestCase
{
    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function test_get_balance(): void
    {
        $auth = [
            'username' => 'cleandev22222',
            'password' => 'cleandev2020'
        ];

        self::assertIsInt(EkoSmsChannel::balance($auth));
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function test_get_balance_auth_error(): void
    {
        $this->expectException(CouldNotSendNotification::class);

        $auth = [
            'username' => 'wrong username',
            'password' => 'wrong password'
        ];

        EkoSmsChannel::balance($auth);
    }
}
