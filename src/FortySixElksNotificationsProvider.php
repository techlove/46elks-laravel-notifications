<?php

namespace Techlove\FortySixElks\Notifications;

class FortySixElksNotificationsProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('techlove-46elks-notifications-message', function () {
            return new Factory\MessageFactory();
        });

        $this->app->bind('techlove-46elks-notifications-client', function () {
            return new Services\MessageClient(
                config('services.46elks.username'),
                config('services.46elks.password')
            );
        });
    }
}