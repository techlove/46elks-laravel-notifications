<?php

namespace Techlove\FortySixElks\Notifications\Channels;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Techlove\FortySixElks\Notifications\Exceptions\ChannelException;
use Techlove\FortySixElks\Notifications\Messages;

class FortySixElksChannel
{
    public function __construct(protected Dispatcher $events)
    {
    }

    public function send(mixed $notifiable, Notification $notification)
    {
        /** @var Notifiable $notifiable */

        if (!method_exists($notification, 'to46Elks')) {
            throw new ChannelException(
                __('Missing to46Elks method on the Notification class :class', ['class' => get_class($notification)])
            );
        }

        /** @var Messages\MessageInterface $message */
        $message = $notification->to46Elks($notifiable);

        if (!$message instanceof Messages\MessageInterface) {
            throw new ChannelException(
                __(
                    'The to46Elks method on the Notification class :class must return instance of :interface',
                    [
                        'class' => $notification::class,
                        'interface' => Messages\MessageInterface::class
                    ]
                )
            );
        }

        $message->routedTo(
            $notifiable->routeNotificationFor('46Elks', $notification)
                ?? data_get($notifiable, 'phone')
        );

        try {
            return $message->send();
        } catch (\Exception $e) {
            $this->events->dispatch(
                new NotificationFailed(
                    $notifiable,
                    $notification,
                    static::class,
                    ['exception' => $e]
                )
            );
        }
    }
}