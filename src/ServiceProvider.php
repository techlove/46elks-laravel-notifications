<?php

namespace Techlove\FortySixElks\Notifications;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('techlove-46elks-notifications-message', function () {
            return new Factory\MessageFactory();
        });

        $this->app->bind(Services\MessageClient::class, function () {
            return new Services\MessageClient(
                config('services.46elks.username'),
                config('services.46elks.password')
            );
        });
    }
}