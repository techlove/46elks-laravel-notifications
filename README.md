# 46elks Laravel Notification Package

Designed to make sending SMS & MMS notifications super easy by providing an easy-to-use but feature-rich channel.

This package aims to address limitations of the laravel-notification-channel/46elks project, including:
1. Ditching BWC support for old PHP & Laravel versions, allowing one to utilize the newest language and framework functionality.
2. Providing event support for messages. This allows you to provide before- and after-send logic.
3. Make the package more easily testable
4. Minimize the payload size to only the payload required. This helps prevents unintended behaviour and reduces the payload size

In the future, it is hoped that this package can also bring:
1. Number validation options
2. In combination with other packages:
   1. Support for creating a database record of the message 
   2. Simple premade implementation of webhook status updating


### Installation

`composer require techlove/46elks-laravel-notifications`

(to install prior to package release, add git@github.com/techlove/46elks-laravel-notifications as a vcs source in your composer.json)

### Usage

```php
<?php

namespace App\Notifications;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notification;
use Techlove\FortySixElks\Notifications\Channels\FortySixElksChannel;
use Techlove\FortySixElks\Notifications\Facades\MessageFacade;use Techlove\FortySixElks\Notifications\Messages\Sms;

class TwoFactorCode extends Notification
{
    public function __construct(public string $token) {}

    public function via(): array
    {
        return [
            FortySixElksChannel::class,
        ];
    }
    
    public function to46Elks(User $user): Sms
    {
        return MessageFacade::sms()
            ->from('YOUR APP')
            ->to($notifiable->phone)
            ->dontLog()
            ->addLines([
                __('Your two factor authentication code: :token', ['token' => $this->token]),
                '~ ' . config('app.name')
            ]);
    }
}
```