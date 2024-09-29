<?php

namespace Techlove\FortySixElks\Notifications\Factory;

use Techlove\FortySixElks\Notifications\Services\MessageClient;
use Techlove\FortySixElks\Notifications\Messages;

class MessageFactory
{
    public function __construct() {}

    public function sms(): Messages\Sms
    {
        return new Messages\Sms($this->getClient());
    }

    public function mms(): Messages\Mms
    {
        return new Messages\Mms($this->getClient());
    }

    /**
     * @param class-string<Messages\MessageInterface> $class
     *
     * @return Messages\MessageInterface
     */
    public function custom(string $class): Messages\MessageInterface
    {
        return new $class($this->getClient());
    }


    protected function getClient(): MessageClient
    {
        return app()->get(MessageClient::class);
    }
}