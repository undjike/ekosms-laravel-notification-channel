<?php

namespace Undjike\EkoSmsNotificationChannel;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class EkoSmsServiceProvider extends ServiceProvider
{
    public const BASE_URL = 'https://api-public.ekotech.cm';

    /**
     * Register the application services.
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @noinspection PhpUnusedParameterInspection
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('ekosms', function ($app) {
                return new EkoSmsChannel();
            });
        });
    }
}
